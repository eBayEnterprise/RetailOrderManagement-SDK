# Change Log
All notable changes to this project will be documented in this file.

## [Unreleased][unreleased]
### Added
- Documentation
  - AMQP and HTTP API
  - Running tests

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

[unreleased]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.0.0
