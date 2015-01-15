# Change Log
All notable changes to this project will be documented in this file.

## [Unreleased]
### Added
- Support for the following Retail Order Management Public API operations:
  - order/create
- Links from child payloads to parent payloads

### Changed
- Consolidated following interfaces to more general namespaces and deprecated old interfaces:
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ICustomAttribute` => `eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttribute`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ICustomAttributeContainer` => `eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeContainer`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ICustomAttributeIterable` => `eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IDestination` => `eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestination`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgram` => `eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgram`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramContainer` => `eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramContainer`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramIterable` => `eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPersonName` => `eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPersonName`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPhysicalAddress` => `eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPhysicalAddress`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IProductDescription` => `eBayEnterprise\RetailOrderManagement\Payload\Order\IProductDescription`
  - `eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails` => `eBayEnterprise\RetailOrderManagement\Payload\Order\IStoreFrontDetails`

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

[Unreleased]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-1...HEAD
[1.1.0-alpha-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.0.0...1.1.0-alpha-1
