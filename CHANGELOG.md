# Change Log
All notable changes to this project will be documented in this file.

## [1.4.4] - 2016-01-07
### Fixed
- Performance issue loading multiple instances of the payload config map

## [1.4.3] - 2015-12-09
### Fixed
- Allow PFO Jurisdiction Level for Tax Payload

## [1.4.2] - 2015-11-19
### Fixed
- Optional address lines in payload return non-null value

## [1.4.1] - 2015-11-16
### Fixed
- Fraud can only handle IPv4 addresses

## [1.4.0] - 2015-11-05
### Added
- Support phone number in StoreLocation Sub-payload

## [1.3.8] - 2015-10-21
### Fixed
- Do not round EffectiveRate

## [1.3.7] - 2015-10-21
### Changed
- Make CVV not required in Credit Card Auth Request Payload

## [1.3.6] - 2015-10-09
### Fixed
- Encode values before serializing to XML

## [1.3.5] - 2015-10-05
### Updated
- Order Common Data Type XSD for StoreFrontDetail and StoreFrontLocation node

## [1.3.4] - 2015-09-30
### Fixed
- Order create destinations out of sequence

## [1.3.3] - 2015-09-30
### Fixed
- Order Detail Payload Invalid Payload Error for payload with no pan is token data

## [1.3.2] - 2015-08-06
### Fixed
- Fix Tax Jurisdiction Level in Order Create Request

## [1.3.1] - 2015-07-30
### Fixed
- ItemDesc is too long for XSD validation in tax/quote

## [1.3.0] - 2015-07-16
### Added
- Support for payments/tendertype/lookup Retail Order Management Public API operation

### Fixed
- UnitAmount field in the PayPal set express request is not supposed to be optional

## [1.2.1] - 2015-07-02
### Fixed
- Invalid xml character sequences were being added to the order create request without being escaped
- Rename Allocation Rollback Payloads
- Fix Allocation Message Config

## [1.2.0] - 2015-06-18
### Added
- Support for inventory/allocations/create Retail Order Management Public API operation
- Support for inventory/allocations/delete Retail Order Management Public API operation

### Fixed
- Inventory Details configured to use xml schema validator instead of xsd schema validator
- `/OrderCreateRequest/.../TimeSpentOnSite` formatting can produce xsd-invalid results

## [1.2.0-alpha-3] - 2015-06-04
### Added
- Support for the inventory/quantity Retail Order Management Public API operation
- Support for the inventory/details Retail Order Management Public API operation
- Support for the order/detail Retail Order Management Public API operation

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

[1.4.4]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.4.3...1.4.4
[1.4.3]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.4.2...1.4.3
[1.4.2]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.4.1...1.4.2
[1.4.1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.4.0...1.4.1
[1.4.0]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.8...1.4.0
[1.3.8]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.7...1.3.8
[1.3.7]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.6...1.3.7
[1.3.6]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.5...1.3.6
[1.3.5]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.4...1.3.5
[1.3.4]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.3...1.3.4
[1.3.3]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.2...1.3.3
[1.3.1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.3.0...1.3.1
[1.3.0]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.2.1...1.3.0
[1.2.1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.2.0...1.2.1
[1.2.0]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.2.0-alpha-3...1.2.0
[1.2.0-alpha-3]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.2.0-alpha-2...1.2.0-alpha-3
[1.2.0-alpha-2]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.2.0-alpha-1...1.2.0-alpha-2
[1.2.0-alpha-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-beta-1...1.2.0-alpha-1
[1.1.0-beta-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-4...1.1.0-beta-1
[1.1.0-alpha-4]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-3...1.1.0-alpha-4
[1.1.0-alpha-3]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-2...1.1.0-alpha-3
[1.1.0-alpha-2]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.1.0-alpha-1...1.1.0-alpha-2
[1.1.0-alpha-1]: https://github.com/eBayEnterprise/RetailOrderManagement-SDK/compare/1.0.0...1.1.0-alpha-1
