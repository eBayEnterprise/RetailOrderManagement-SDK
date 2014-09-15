# Retail Order Management Software Development Kit

by eBay Enterprise

This document is a combination of developer guidelines and specification. (When the work is more mature, this can live in DEVELOPING.md.)

# What do we need to do?

We need to send data to an API and process its response.

## How do we send data to an API?

In this case, we provide a payload object to a transport pub/sub.

### How does transport work?

Transport uses the [Observer Pattern][1], for which PHP already provides [SplSubject][2] and [SplObserver][3] interfaces.

The Transport Subject is an SplSubject that composes the following:

- The Transporter: An object that knows generically how to transport data (e.g. a pecl_http HttpRequest object)
- The Configuration: An object that knows how to configure the transport object (e.g. by providing an address as a URI, providing specific credentials for authentication, etc.)
- The Serializer: An object that knows how to serialize the payload object
- The Payload: The aforementioned payload object

When the transport subject's `send` method is invoked it will deliver the payload to the configured destination. When the transport object returns a response, the transport subject will make that response available via its `notify` method. (Whether the transport object is synchronous or asynchronous.) The response is provided raw to the SplObserver of the `notify`.

## How can we process the response from the API?

The Transport Observer is an SplObserver that composes the following:

- The Receiver: An object that knows how to route the raw response from the transport object into either...
- The Deserializer: An object that knows how to convert a string into a payload object
- The Error Handler: An object that knows how to handle errors (*)

*Need clarity on the error handler: Is it just handling large scale errors that can't be detected synchronously during transport?

[1]: http://en.wikipedia.org/wiki/Observer_pattern
[2]: http://php.net/manual/en/class.splsubject.php
[3]: http://php.net/manual/en/class.splobserver.php
