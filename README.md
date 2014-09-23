# Retail Order Management Software Development Kit

by eBay Enterprise

This document is a combination of developer guidelines and specification. (When the work is more mature, this can live in DEVELOPING.md.)

## The Problem

Our implementation of Retail Order Management as a Magento extension is too tightly coupled to Magento 1. Among other things, that means

- Transition to Magento 2 may be problematic.
- We are prevented from using PHP best practices because Magento 1 does not support many of them.
- We have to know too much about the API itself, from HTTP to XML.

## What do we need to do?

We need a PHP implementation of the Retail Order Management API(s) that hides unnecessary details such as request/response handling and XML parsing from the API user in order to provide a minimal interface for remote messages and procedure calls.

## How do we do that?

Explicitly. For each service and operation, we will attempt to build a flat, clean and consistent interface. We'll use abstraction to provide consistent behavior between different services and operations, but you will have to craft the concrete implementations to smooth over the rough parts of the API.

Let's start with a concrete, but prototypical usage example*:

#### Example Usage: Getting the Api Object

```php
use \eBayEnterprise\RetailOrderManagement\Api;
class EbayEnterprise_CreditCard_Helper_Data
{
    /**
     * Get the preconfigured api object.
     * There are several Magento-specific issues I'm ignoring here, such as multi-store configuration diffs.
     *
     * @return Api
     */
    public function getApi();
    {
        $cfg = $this->getConfigModel();
        $apiKey = $cfg->apiKey;
        $host = $cfg->apiHostname;
        $majorVersion = $cfg->apiMajorVersion;
        $minorVersion = $cfg->apiMinorVersion;
        $storeId = $cfg->storeId;
        $service = 'payments';
        $operation = 'creditcard/auth/VC';
        $apiConfig = new HttpApiConfig($apiKey, $host, $majorVersion, $minorVersion, $storeId, $service, $operation);
        return new HttpApi($apiConfig);
    }
}
```

The API object needs particular values just to exist at all, so those must be provided in the constructor, and are specifically:

- host
- major and minor version
- store id
- service
- operation

and optionally

- format ('xml' by default)
- query string parameters (nothing by default)

The API instance stores these values and uses them to construct the full URL to the service.

#### Example Usage: Authorizing a Credit Card

```php
use \eBayEnterprise\RetailOrderManagement;
class EbayEnterprise_CreditCard_Model_Method
{
    /**
     * Authorize payment so OMS can capture later.
     *
     * @param Mage_Sales_Model_Order_Payment $payment
     * @param float $amount
     * @return self
     */
    public function authorize(Varien_Object $payment, $amount)
    {
        $billingAddress = $this->getBillingAddress($payment);
        $shippingAddress = $this->getShippingAddress($payment);
        $api = $this->_helper->getApi();
        $requestId = $this->getRequestId();
        /**
         * @var Payload\PaymentService\FlatCreditCardAuth $requestPayload
         *      Here, we assume there's a payload implementation for credit card auth
         *      that's fairly flat. That's not a requirement of the interface, but
         *      seems easiest to work with right now.
         */
        $requestPayload = $api->getRequestBody();
        $requestPayload
            ->setRequestId($requestId)
            ->setOrderId($payment->getOrderId())
            ->setPanIsToken($payment->getPanIsToken())
            ->setCardNumber($payment->getPan())
            ->setExpirationDate($payment->getExpirationDate())
            ->setCardSecurityCode($payment->getCardSecurityCode())
            ->setAmount($amount)
            ->setCurrencyCode($payment->getCurrency())
            ->setEmail($billingAddress->getEmail())
            ->setIp($payment->getIp())
            ->setBillingFirstName($billingAddress->getFirstname())
            ->setBillingLastName($billingAddress->getLastname())
            ->setBillingPhone($billingAddress->getTelephone())
            ->setBillingLine1($billingAddress->getStreet1())
            ->setBillingLine2($billingAddress->getStreet2())
            ->setBillingLine3($billingAddress->getStreet3())
            ->setBillingLine4($billingAddress->getStreet4())
            ->setBillingCity($billingAddress->getCity())
            ->setBillingMainDivision($billingAddress->getRegionCode())
            ->setBillingCountryCode($billingAddress->getCountry())
            ->setBillingPostalCode($billingAddress->getPostcode())
            ->setShipToFirstName($shippingAddress->getFirstname())
            ->setShipToLastName($shippingAddress->getLastname())
            ->setShipToPhone($shippingAddress->getTelephone())
            ->setShipToLine1($shippingAddress->getStreet1())
            ->setShipToLine2($shippingAddress->getStreet2())
            ->setShipToLine3($shippingAddress->getStreet3())
            ->setShipToLine4($shippingAddress->getStreet4())
            ->setShipToCity($shippingAddress->getCity())
            ->setShipToMainDivision($shippingAddress->getRegionCode())
            ->setShipToCountryCode($shippingAddress->getCountry())
            ->setShipToPostalCode($shippingAddress->getPostcode());
            // ->setIsRequestToCorrectCvvOrAvsError(false)
            // ->setSecureVerificationData(???)
        $api->setRequestBody($requestPayload);
        try {
            $api->send();
        } catch (Payload\Exception\InvalidPayload $e) {
            $this->_log->logWarn('[%s] %s', array(__CLASS__, $e->getMessage()));
            throw Mage::exception('Mage_Core', 'Unable to validate credit card info');
        } catch (Api\Exception\NetworkError $e) {
            $this->_log->logWarn('[%s] %s', array(__CLASS__, $e->getMessage()));
            throw Mage::exception('Mage_Core', 'Unable to authorize payment at this time. Please try again later.');
        }

        /** @var Payload\IPayloadIterator $responses */
        $responses = $api->getResponsePayloadIterator();
        while ($responses->valid()) {
            try {
                /** @var Payload\PaymentService\CreditCardAuthResponse $ccAuthResponse */
                $ccAuthResponse = $responses->current();
            } catch (Payload\Exception\UnexpectedResponse $e) {
                $this->_log->logWarn('[%s] %s', array(__CLASS__, $e->getMessage()));
                throw Mage::exception('Mage_Core', 'Unable to authorize payment at this time. Please try again later.');
            }
            // Only expecting one response here; so we can just `break`,
            // but calling next() should result in a `false` on the loop condition.
            $responses->next();
        }

        if ($this->validateAuthCode($ccAuthResponse->getAuthorizationResponseCode()) &&
            $this->validateBankCode($ccAuthResponse->getBankAuthorizationCode()) &&
            $this->validateCvvCode($ccAuthResponse->getCvvCode())) {

            $payment->setResponsePayload($ccAuthResponse);
            $payment->setTransactionId($requestId);
        }

        return $this;
    }
}
```

The interesting parts are:

- `getEmptyRequestPayload()`, which returns a new, empty payload object specific to the service/operation request that API instance was instantiated with.
- `updateRequestPayload($pld)`, which merges any new information on top of what the API already knows about.
- You can set the payload fields in any order as long as all required business data is supplied to the api before calling `send` on the api or `validate` on the payload object (the latter not shown).
- When you call `send`, the api:
    1. validates the payload, which:
        - Alters any values that can be trivially altered to pass validation, such as by truncation or numeric conversion.
        - Attempts to construct an xml string from the payload.
        - Attempts to xsd-validate the resultant string.
        - Throws an InvalidPayload if the xml cannot be constructed.
    2. sets up an http request object and POSTs the serialized request payload
        - Blocks, waiting for a response
        - Throws a NetworkError if the http object can't connect to the host or the connection times out or is closed unexpectedly
    3. attempts to parse the response into a response payload object
        - Throws an UnexpectedResponse if it can't
        - Otherwise, the response payload is available at `getResponsePayload()` and contains fields from the response.

## Payloads, in general

The payloads need to be crafted specifically for each service/operation, but there are some general guidances:

1. Make things flat when possible. In a few cases (such as when maxOccurrences > 1) we can't assume there will only be one of a node, so the payload can't be perfectly flat. In these cases the payload should have _add_ operations as well as the normal `set`. Here's an example usage as it might be seen in order create:

```php
use \eBayEnterprise\RetailOrderManagement\Payload;
$orderCreatePayload = $orderCreateApi->getEmptyRequestPayload();
…
foreach ($items as $item) {
    $itemPayload = $orderCreatePayload->getEmptyOrderItemRequestType();
    $itemPayload
        ->setItemId('abc123')
        ->setQuantity(3)
        …;
    $orderCreatePayload->addItemPayload($itemPayload); // e.g. not an update
}
```

2. The payload should be retrievable from the api object, but it should also be distinct from it. It will be useful to be able to recover a payload far down the line (for example, to see the credit card auth reply during order create), but the api object it came from should be much more perishable. Thus, you should be able to detach the payload from the api object and store it independently.

## Network Issues

The API operates over the TCP/IP stack. The design is intended to abstract the various differences between application protocols, so the `NetworkError` type is very generic. Besides the error object itself, details about what happened should be accessible from the api object. This will allow the api to be used both synchronously and asynchronously, so that error handlers can get details from the api object when error _events_ happen. For example, in HTTP:

```php
try {
    $api->send();
} catch (Api\Exception\NetworkError $e) {
    // You don't have to use this exception for anything. It's here for your convenience.
    // If this exception occurs, though, then you must assume certain other properties won't be well-defined:
}

$httpMessage = $api->getHttpMessage();
$httpStatus = $api->getHttpMessage()->getResponseCode();
```

*The examples assume some things about the implementation that may not make it into the real world. We need to try not to change the interface, but things like constructor argument order, or even the use of non-default constructors at all, may change.
