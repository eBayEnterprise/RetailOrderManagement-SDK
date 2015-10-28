[![ebay logo](docs/static/logo-vert.png)](http://www.ebayenterprise.com/)

# Retail Order Management Software Development Kit

[![unit test status](https://circleci.com/gh/eBayEnterprise/RetailOrderManagement-SDK/tree/master.svg?style=shield&circle-token=e66bede58bad92544f0228cd361f1beda141b794)](https://circleci.com/gh/eBayEnterprise/RetailOrderManagement-SDK)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/eBayEnterprise/RetailOrderManagement-SDK/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/eBayEnterprise/RetailOrderManagement-SDK/?branch=master)

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

The HTTP API is a transport mechanism for communicating with the web service APIs. It facilitates creating, sending and recieving payloads of a message.

```php
// Use an HttpConfig instance to configure an HttpApi object for a message.
$apiConfig = new \eBayEnterprise\RetailOrderManagement\Api\HttpConfig(
    $apiKey, // authentication token for connecting to the api
    $apiHostname,
    $apiMajorVersion, // major version number of the service
    $apiMinorVersion, // minor version number of the service
    $storeId, // Retail Order Management store identifier
    $service, // type of service to communicate with (e.g. payments)
    $operation, // string representing the operation to be performed (e.g. creditcard/auth)
    $endPointParams = [] // optional, extra parameters for the request.
);
$httpApi = new \eBayEnterprise\RetailOrderManagement\Api\HttpApi($apiConfig);

try {
    // get the request payload for the message.
    $request = $httpApi->getRequestBody();
    // set the payload as the message body and send the request.
    $httpApi->setRequestBody($request)
        ->send();
    $reply = $httpApi->getResponseBody();
    // process response data

} catch (\eBayEnterprise\RetailOrderManagement\Payload\Exception\UnsupportedOperation $e) {
    // Exception may be thrown from: { send, getRequestBody, getResponseBody }
    // Handle the case where the service and operation specified in the configuration has no matching payload.
    print $e->getMessage();

} catch (\eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedHttpAction $e) {
    // Exception may be thrown from: { send }
    // handle the case where the http method is configured with an invalid value
    print $e->getMessage();

} catch (\eBayEnterprise\RetailOrderManagement\Api\Exception\NetworkException $e) {
    // Exception may be thrown from: { send }
    // handle the case where the request takes longer than the timeout threshold or if the connection cannot be made or is lost
    print $e->getMessage();

} catch (\eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload $e) {
    // Exception may be thrown from: { send, getRequestBody, getResponseBody }
    // handle the case where a payload fails validation
    print $e->getMessage();
}
```

## AMQP API

Use the AMQP API to respond to batches of Retail Order Managment events.

```php
// Similar to the HttpApi, start with by filling out a configuration object
$apiConfig = new Api\AmqpConfig(
    $connectionType, // string that configures the way the api connects to a queue.
                     // Use '\PhpAmqpLib\Connection\AMQPSSLConnection' to connect using ssl.
                     // Use '\PhpAmqpLib\Connection\AMQPConnection' to connect without ssl.
    $maxMessagesToProcess, // The number of message to process per batch
    $connectionHostname,
    $connectionPort,
    $connectionUsername,
    $connectionPassword,
    $connectionVhost,
    array $connectionContext,
    $connectionInsist,
    $connectionLoginMethod,
    $connectionLocale,
    $connectionTimeout,
    $connectionReadWriteTimeout,
    $queueName,
    // flags
    $queuePassive,
    $queueDurable,
    $queueExclusive,
    $queueAutoDelete,
    $queueNowait
);
$amqpApi = new Api\HttpApi($apiConfig);

// Get a PayloadIterator object in order to process messages.
$payloads = $amqpApi->fetch();

// Use `valid` to control the iteration over the messages. Each call attempts
// to retreive a message. If a message is received it is `ack`ed immediately.
while ($payloads->valid()) {
    try {
        // The AmqpApi does not deserialize payloads as it recieves them.
        // Deserialization happens during the call to PayloadIterator::current().
        $payload = $payloads->current();
    } catch (\eBayEnterprise\RetailOrderManagement\Payload\Exception\Payload $e) {
        // While iterating through the payloads, a Payload exception may be
        // thrown and cause the premature end of the loop unless caught.

        print $e->getMessage();
    }
    // advance the internal pointer to the next payload
    $payloads->next();
}
```

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

After composer has installed all dependencies, tests can be run from the
SDK's root directory.

```sh
vendor/bin/phpunit
vendor/bin/phpmd src text phpmd.xml
vendor/bin/phpcs --standard=psr2 src
```

[Composer]: https://getcomposer.org/
[Docker]: https://www.docker.com/
[Fig]: http://www.fig.sh/
