# Retail Order Management Software Development Kit

by eBay Enterprise

A PHP implementation of the Retail Order Management API(s) that hides unnecessary details such as request/response handling and XML parsing from the API user in order to provide a minimal interface for remote messages and procedure calls.

Requires PHP 5.4 and later.

Compatible with Retail Order Management schema version 1.8.20.

## Setup

For best results, install via [Composer].

In composer.json:

```json
"require": {
    "ebayenterprise/retail-order-management": "~1.0"
}
```

Or with the Composer CLI:

```bash
php composer.phar require ebayenterprise/retail-order-management:~1.0
```

## Payloads

Payloads represent the data that is sent or received through the SDK.

```php
// The payload factory can be used to create any of the
// supported payloads types.
$payloadFactory = new \eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
// Instantiate a payload object with the factory by passing
// the full class name of the payload to the factory.
$payload = $payloadFactory
    ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceRequest');

// Payloads can be populated with data by:

// Calling setters for all of the required data.
$payload->setCardNumber('11112222')
    ->setPanIsToken(false)
    ->setRequestId('1234567890')
    ->setPin('5555')
    ->setCurrencyCode('USD');

// Deserializing a serialized set of data.
$payload->deserialize('<StoreValueBalanceRequest>...</StoredValueBalanceRequest>');

// Complete payload can now be validated.
try {
    $payload->validate();
} catch (\eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload $e) {
    // The payload is invalid. The exception message, $e->getMessage(),
    // will contain details of the validation error.
}

// Serializing a payload will produce an XML representation of the payload.
$payload->serialize();
```

### Request Payloads

Request payloads represent a set of data to be sent across the SDK.

```php
/** @var \eBayEnterprise\RetailOrderManagement\Api\HttpApi $api */
$api;

// Request payloads will be created as necessary by the transport mechanism
// that will be sending the payload.
$payload = $api->getRequestBody();

// The payload should be populated with data necessary to make the call
// using the SDK.

// Payload interfaces expose methods to set data the data piecemeal.
$payload->setCardNumber('11112222')
    ->setPanIsToken(false)
    ->setRequestId('1234567890')
    ->setPin('5555')
    ->setCurrencyCode('USD');

// A serialized payload may also be deserialized to set all of the data
// in the serialization on the payload.
$payload->deserialize('<StoreValueBalanceRequest>...</StoreValueBalanceRequest>');

// Once the payload has been populated, it can be given back to the
// API and sent.
$api->setRequestBody($payload)->send();
```

### Reply Payload

Reply payloads represent sets of data retrieved from the SDK.

```php
// Get the reply payload from the API object, in this case the
// response from an HTTP API call. Assume $httpApi to be an
// \eBayEnterprise\RetailOrderManagment\Api\HttpApi object.
$payload = $httpApi->getResponseBody();

// If a payload was populated by the SDK, it will have been
// validated automatically. Validation can still be done on demand
// if desired.
try {
    $payload->validate();
} catch (\eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload $e) {
    // The payload is invalid. The exception message, $e->getMessage(),
    // will contain details of the validation errors.
}

// Get methods will be present for any data in the payload.
$payload->getOrderId();
$payload->getCurrencyCode();
```

### Sub-Payloads

The majority of payloads in the SDK are flat, all necessary data is set within a single payload object. In some cases, however, a payload will contain additional nested payloads.

```php
/** @var \eBayEnterprise\RetailOrderManagment\Payload\OrderEvents\OrderShipped $payload */
$payload;

// Some payloads will contain an iterable of sub-payloads. In this case,
// $loyaltyPrograms will be an interable payload containing a collection
// of loyalty program payloads.
$loyaltyPrograms = $payload->getLoyaltyPrograms();

// The iterable is a complete payload and can be serialized, deserialized and
// validated like any other payload.
$loyaltyPrograms->validate();
$loyaltyPrograms->serialize();
$loyaltyPrograms->deserialize('<LoyaltyPrograms><LoyaltyProgram>...<LoyaltyProgram><LoyaltyProgram>...<LoyaltyProgram></LoyaltyPrograms>');

foreach ($loyaltyPrograms as $program) {
    // The objects in the iterable area also complete payloads.
    $program->validate();
    $program->setAccount('ABCDEFG');
}

// Iterable payloads will always provide a way of getting empty payloads
// that can be added to the iterable.
$loyaltyProgram $loyaltyPrograms->getEmptyLoyaltyProgram();

// Payload can now be filled out and added to the iterable.
$loyaltyProgram->setAccount('XYZ')->setProgram('RewardProgram');
$loyaltyPrograms->attach($loyaltyProgram);

// Sub-payloads may also be used to create a separate container of data
// within a payload or when a set of data cannot be trivially flattened
// into a single payload.
$destination = $payload->getShippingDestination();

// The shipping destination may be a mailing address (shipped to a customer)
// or a store front location (shipped to a retail store).
if ($destination instanceof \eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress) {
    $destination->getFistName();
    $destination->getLastName();
} elseif ($destination instanceof \eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails) {
    $destination->getStoreName();
    $destination->getHours();
}

// In both cases, the object returned will still be a complete payload and
// can be treated as such.
$destination->validate();
$destination->deserialize();
```

## HTTP API

TBD

## AMQP API

TBD

## Tests

### Using [Docker]

A [fig file](fig.yml) is included to automate creating and coordinating [Docker] containers to install and run tests.

To install and run tests using [Fig]:

```sh
# setup and install
fig run --rm setup
fig run --rm composer install
# run tests
fig run --rm phpunit
```

See [fig.yml](fig.yml) for additional commands for automated tests and static analysis.

See [Docker] and [Fig] for additional installation and usage information.

### Local with [Composer]

TBD

[Composer]: https://getcomposer.org/
[Docker]: https://www.docker.com/
[Fig]: http://www.fig.sh/
