# Change Log
All notable changes to this project will be documented in this file.

## [1.1.0-alpha-1][1.1.0-alpha-1] - 2015-01-29
### Fixed
- Make PayPal Express Checkout functional when `Transfer Cart Line Items` is turn off in the backend.

### Added
- Documentation for AMQP and HTTP API
- Documentation for running tests

## 1.0.0 - 2015-01-15
### Added
- Initial release
- Compatible with Retail Order Management schema version 1.8.20
- Support for the following Retail Order Management Public API operations:
  - payments/creditcard/auth
  - payments/paypal/doAuth
  - payments/paypal/doExpress
  - payments/paypal/getExpress
  - payments/paypal/setExpress
  - payments/paypal/void
  - payments/storedvalue/balance
  - payments/storedvalue/redeem
  - payments/storedvalue/redeemvoid
- Support for the following Retail Order Management Order Events:
  - OrderAccepted
  - OrderBackorder
  - OrderCancelled
  - OrderConfirmed
  - OrderCreditIssued
  - OrderGiftCardActivation
  - OrderPriceAdjustment
  - OrderRejected
  - OrderReturnInTransit
  - OrderShipped
  - Test
- HTTP API for bidirectional communication.
- AMQP API for unidirectional messages.

[1.1.0-alpha-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.0.0...1.1.0-alpha-1
