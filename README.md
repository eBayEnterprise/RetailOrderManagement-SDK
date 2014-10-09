# Retail Order Management Software Development Kit

by eBay Enterprise

## The Problem

Our implementation of Retail Order Management as a Magento extension is too tightly coupled to Magento 1. Among other things, that means

- Transition to Magento 2 may be problematic.
- We are prevented from using PHP best practices because Magento 1 does not support many of them.
- We have to know too much about the API itself, from HTTP to XML.

## What the SDK does

The SDK is a PHP implementation of the Retail Order Management API(s) that hides unnecessary details such as request/response handling and XML parsing from the API user in order to provide a minimal interface for remote messages and procedure calls.

## How does it do that?

Explicitly. For each service and operation, we attempt to build a flat, clean and consistent interface. We use abstraction to provide consistent behavior between different services and operations to smooth over the rough parts of the API.

Let's start with a concrete, but prototypical usage example*:

The API object needs particular values just to exist at all, so those must be provided by the config object. For HTTP, they are:

- API key
- host
- major and minor version
- store id
- service
- operation

and optionally

- format ('xml' by default)
- query string parameters (nothing by default)

The API instance stores these values and uses them to construct the full URL to the service.

#### Example Usage: Creating and configuring the Api Object

```
/**
	 * Create a new ROM SDK API object. API will be configured with core configuration
	 * and the service and operation provided.
	 * @param  string $service   SDK API service
	 * @param  string $operation SDK API operation
	 * @return Api\IBidirectionalApi
	 */
	public function getSdkApi($service, $operation)
	{
		$config = $this->getConfigModel();
		$apiConfig = new Api\HttpConfig(
			$config->apiKey,
			$config->apiHostname,
			$config->apiMajorVersion,
			$config->apiMinorVersion,
			$config->storeId,
			$service,   // ex.: payments
			$operation  // ex.: creditcard/auth/VC
		);
		return new Api\HttpApi($apiConfig);
	}
```
 
Once you have a valid Api object you will need to configure the request payload according to the ROM service and operation you want. The Api object will create the appropriate request payload type based on the configuration passed in the constructor. 
  - Ask the Api object for a request payload
   - `$request = $api->getRequestBody();`
  - Configure the paylod

#### Example Usage: Authorizing a Credit Card

```
/**
	 * Fill out the request payload with payment data and update the API request
	 * body with the complete request.
	 * @param Api\IBidirectionalApi $api
	 * @param Varien_Object $payment Most likely a Mage_Sales_Model_Order_Payment
	 * @return self
	 */
	protected function _prepareApiRequest(Api\IBidirectionalApi $api, Varien_Object $payment)
	{
		$request = $api->getRequestBody();
		$order = $payment->getOrder();
		$billingAddress = $order->getBillingAddress();
		$shippingAddress = $order->getShippingAddress() ?: $billingAddress;
		$request
			->setRequestId($this->_coreHelper->generateRequestId('CCA-'))
			->setOrderId($payment->getOrder()->getIncrementId())
			->setPanIsToken(true)
			->setCardNumber($payment->getCcNumber())
			// use first day of month/year so the date can be recognized and parsed
			->setExpirationDate($this->_coreHelper->getNewDateTime(sprintf('01/%s/%s', $payment->getCcExpMonth(), $payment->getCcExpYear())))
			->setCardSecurityCode($payment->getCcCid())
			->setAmount($payment->getBaseAmountAuthorized())
			->setCurrencyCode(Mage::app()->getStore()->getBaseCurrencyCode())
			->setEmail($billingAddress->getEmail())
			->setIp($this->_httpHelper->getRemoteAddr())
			->setBillingFirstName($billingAddress->getFirstname())
			->setBillingLastName($billingAddress->getLastname())
			->setBillingPhone($billingAddress->getTelephone())
			->setBillingLines($billingAddress->getStreet(-1)) // getStreet(-1) returns all lines, nl separated
			->setBillingCity($billingAddress->getCity())
			->setBillingMainDivision($billingAddress->getRegionCode())
			->setBillingCountryCode($billingAddress->getCountry())
			->setBillingPostalCode($billingAddress->getPostcode())
			->setShipToFirstName($shippingAddress->getFirstname())
			->setShipToLastName($shippingAddress->getLastname())
			->setShipToPhone($shippingAddress->getTelephone())
			->setShipToLines($shippingAddress->getStreet(-1)) // getStreet(-1) returns all lines, nl separated
			->setShipToCity($shippingAddress->getCity())
			->setShipToMainDivision($shippingAddress->getRegionCode())
			->setShipToCountryCode($shippingAddress->getCountry())
			->setShipToPostalCode($shippingAddress->getPostcode())
			->setIsRequestToCorrectCVVOrAVSError((bool) $payment->getAdditionalInformation('is_correction_request'));
	}
```
  - Call `api->send()` to execute the request
  - Call `api->getResponseBody()` to check the result
	


#### Example Usage: Validating the response
```
/**
	 * check is success
	 * - true - return, payment was authorized successfully
	 * check is avs errors
	 * - true - throw exception? w/ AVS error message and redir to billing address
	 * check is cvv errors
	 * - true - throw exception? w/ CVV error message and redir to payment
	 * check is acceptable
	 * - true - return, payment may not be a complete success but is still acceptable
	 *   with no additional fixes/changes needed - timeout maybe?
	 */
	protected function _validateResponse(Payload\Payment\CreditCardAuthReply $response)
	{
		if ($response->getIsAuthSuccessful()) {
			return $this;
		}
		throw Mage::exception('EbayEnterprise_CreditCard', $this->_helper->__('Credit card could not be authorized.'));
	}
```
The interesting parts are:

- `getRequestBody()`, which returns a new, empty payload object specific to the service/operation request that API instance was instantiated with.
- `setRequestBody($payload)`, which sets the request payload.
- You can set the payload fields in any order as long as all required business data is supplied to the api before calling `send` on the api or `validate` or `serialize` on the payload object (the latter not shown).
- When you call `send`, the api:
    1. validates the payload, which:
        1. Does any supplied validation; e.g. business validation in addition to xsd validation.
        2. Attempts to construct an xml string from the payload.
        3. Throws an InvalidPayload if the xml cannot be constructed.
    2. sets up an http request object and POSTs the serialized request payload
        - Blocks, waiting for a response
        - Throws a NetworkError if the http object can't connect to the host or the connection times out or is closed unexpectedly
- When you call `getResponseBody`, the api attempts to parse the response into a response payload object, and throws an `UnexpectedResponse` if it can't

## Guidelines

Note: Please use [PSR style](STYLE.md) for this codebase, as opposed to the old style we use for Magento 1 code.

### Payloads

The payloads need to be crafted specifically for each service/operation, but they can all be serialized, deserialized and validated. General guidance:

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

2. The payload should be retrievable from the api object, but it should also be distinct from it. It will be useful to be able to recover a payload far down the line (for example, to see the credit card auth reply during order create), but the api object it came from can perish. Thus, you should be able to detach the payload from the api object and store it independently.

### Network Issues

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

### Implementing Interfaces

The interfaces define certain contracts that implementations must follow. Even though PHP doesn't always provide the means to statically guarantee adherence to the contract, you, the developer, should not consider them optional (even the things in the docblocks). An implementation may (probably even should) have some public methods that are not mentioned in the interface, but please try to understand if you're creating a new normative type as a pure implementation, and bring that up for discussion. In other words, don't create new defacto interfaces unconsciously.

As a rule, interfaces make the best type hints because the parameters to a method should only care what a passed object does, not how it does it. Anyway, you should always require the least specific type that does exactly what you need. Assume that:

1. You can't change the type later.
2. Someone will subclass your class later and abuse anything you didn't specify.

The contract idea means that if I say a method (e.g. `setRequestBody`) takes an interface type (e.g. `IPayload`), I'm guaranteeing statically that I can validate and serialize that object, but beyond that I don't care _how_ the payload does it. So, for example, this does not restrict the payload to doing all its validation internally. The below is a perfectly acceptable example of a partial implementation:

```php
/**
 * Uses an injected validator to actually validate the payload object
 */
abstract class ExternallyValidatedPayload implements IPayload
{
    function __construct(IValidator $v) {
        $this->validator = $v;
    }
    function validate() {
        $this->validator->validate($this);
    }
    // ...
}
```

Anyway, this whole interface thing is designed to limit what you have to worry about when you're implementing a thing. It's a scope-control mechanism, so if your class does a whole lot more than the interface promises, you might be doing too much.

## More Later

I anticipate later additions to this document to include more specific interfaces for unidirectional apis such as feed and queues. We need to have a concrete Http implementation first, as a proof of concept.

*The examples assume some things about the implementation that may not make it into the real world. We need to try not to change the interface, but things like constructor argument order, or even the use of non-default constructors at all, may change.
