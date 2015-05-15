# Change Log
All notable changes to this project will be documented in this file.

## Unreleased
### Added
- Support for the inventory/quantity Retail Order Management Public API operation

## [1.2.0-alpha-2] - 2015-05-21
### Added
- Support for the order/cancel Retail Order Management Public API operation
- Support for the order/summary Retail Order Management Public API operation

### Fixed
- "NONE" tax type missing

## [1.2.0-alpha-1] - 2015-05-07
### Added
- Support for the tax/quote Retail Order Management Public API operation

## [1.1.0-beta-1] - 2015-04-09
### Added
- Billing and shipping address status fields to the PayPal Get Express reply

## [1.1.0-alpha-4] -  2015-03-26
### Added
- Constant values for the taxAndDutyDisplay attribute

### Changed
- Relaxed the validation constraints of some payloads

## [1.1.0-alpha-3] - 2015-02-26
### Added
- Support for the address/validate Retail Order Management Public API operation

## [1.1.0-alpha-2] - 2015-02-12
### Added
- Support for the order/create Retail Order Management Public API operation
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

## [1.1.0-alpha-1] - 2015-01-29
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

[1.2.0-alpha-2]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.2.0-alpha-1...1.2.0-alpha-2
[1.2.0-alpha-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-beta-1...1.2.0-alpha-1
[1.1.0-beta-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-4...1.1.0-beta-1
[1.1.0-alpha-4]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-3...1.1.0-alpha-4
[1.1.0-alpha-3]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-2...1.1.0-alpha-3
[1.1.0-alpha-2]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-1...1.1.0-alpha-2
[1.1.0-alpha-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.0.0...1.1.0-alpha-1