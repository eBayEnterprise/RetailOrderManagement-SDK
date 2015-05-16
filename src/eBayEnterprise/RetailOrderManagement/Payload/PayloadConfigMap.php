<?php
/**
 * Copyright (c) 2013-2014 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2013-2014 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Wrap include in a function to allow variables while protecting scope.
 * @return array mapping of config keys to payload configurations.
 */
return call_user_func(function () {
    $map = []; // This is what we eventually return

    // Common types injected into payloads, referenced in payload mappings.

    // validator types
    $iterableValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\IterablePayload';
    $optionalGroupValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\OptionalGroup';
    $optionalSubpayloadValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\OptionalSubpayloads';
    $requiredFieldsValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields';
    $subpayloadValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\Subpayloads';
    // payload validator iterables - contain validator types
    $validatorIterator = '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator';
    // xsd, xml or other schema validator types
    $xmlValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XmlValidator';
    $xsdSchemaValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator';
    // payload map types
    $payloadMap = '\eBayEnterprise\RetailOrderManagement\Payload\PayloadMap';

    // Common sets of required fields, typically consiting of required fields
    // implemented by a common trait and reused by multiple payloads.
    $paymentAccountUniqueIdParams = ['getCardNumber'];
    $paymentContextParams = array_merge($paymentAccountUniqueIdParams, ['getOrderId']);
    $shippingAddressParams = ['getShipToLines', 'getShipToCity', 'getShipToCountryCode'];
    $physicalAddressParams = ['getLines', 'getCity', 'getCountryCode'];
    $personNameParams = ['getLastName', 'getFirstName'];
    $orderEventItemParams = ['getLineNumber', 'getItemId', 'getQuantity', 'getTitle'];
    $orderPaymentContextParams = ['getOrderId', 'getTenderType', 'getPanIsToken', 'getAccountUniqueId', 'getPaymentRequestId'];
    $customAttributeParams = ['getKey', 'getValue'];
    $iLineItemContainerParams = ['getLineItemsTotal', 'getShippingTotal', 'getTaxTotal',];
    $taxDataParams = ['getType', 'getTaxability', 'getSitus', 'getEffectiveRate', 'getCalculatedTax'];
    $addressValidationHeaderParams = ['getMaxSuggestions'];

    // Payload validators shared by multiple payloads.
    $prepaidPaymentValidators = [
        ['validator' => $requiredFieldsValidator, 'params' => ['getAmount']],
        ['validator' => $optionalSubpayloadValidator, 'params' => ['getCustomAttributes']],
    ];

    // Child payload configuration for payment payloads with payment line items.
    $iLineItemIterableChildPayloads = [
        'payloadMap' => $payloadMap,
        'types' => [
            '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable' =>
                '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'
        ]
    ];
    // Common configuration for a payload that does not include child payloads.
    $noChildPayloads = [
        'payloadMap' => $payloadMap,
        'types' => [],
    ];

    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    $shippingAddressParams,
                    [
                        'getRequestId',
                        'getExpirationDate',
                        'getCardSecurityCode',
                        'getAmount',
                        'getCurrencyCode',
                        'getBillingFirstName',
                        'getBillingLastName',
                        'getBillingPhone',
                        'getBillingLines',
                        'getBillingCity',
                        'getBillingCountryCode',
                        'getEmail',
                        'getIp',
                        'getShipToFirstName',
                        'getShipToLastName',
                        'getShipToPhone',
                        'getIsRequestToCorrectCVVOrAVSError',
                    ]
                ),
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getAuthenticationAvailable',
                    'getAuthenticationStatus',
                    'getCavvUcaf',
                    'getTransactionId',
                    'getPayerAuthenticationResponse',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getAuthorizationResponseCode',
                        'getBankAuthorizationCode',
                        'getCvv2ResponseCode',
                        'getAvsResponseCode',
                        'getAmountAuthorized',
                        'getCurrencyCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentAccountUniqueIdParams,
                    ['getCurrencyCode']
                ),
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getPin',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentAccountUniqueIdParams,
                    [
                        'getResponseCode',
                        'getBalanceAmount',
                        'getCurrencyCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getRequestId',
                        'getAmount',
                        'getCurrencyCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getPin',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getResponseCode',
                        'getAmountRedeemed',
                        'getCurrencyCodeRedeemed',
                        'getBalanceAmount',
                        'getBalanceCurrencyCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest'] = [
        'validators' =>
            $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest']['validators'],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $paymentContextParams,
                    [
                        'getResponseCode',
                    ]
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TestMessage'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getTimestamp',],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderAccepted'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getOrderAcceptedSource',
                    'getTotalAmount',
                    'getTaxAmount',
                    'getVatTaxAmount',
                    'getSubtotalAmount',
                    'getDutyAmount',
                    'getFeesAmount',
                    'getDiscountAmount',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems',
                    'getPayments',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItem',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderAcceptedPaymentIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderShipped'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getTotalAmount',
                    'getTaxAmount',
                    'getSubtotalAmount',
                    'getDutyAmount',
                    'getFeesAmount',
                    'getDiscountAmount',
                    'getShippedAmount',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems',
                    'getShippingDestination',
                    'getPayments',
                    'getTaxDescriptions'
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItem',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PaymentIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ITaxDescriptionIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TaxDescriptionIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderReturnInTransit'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCurrencyCode',
                    'getCurrencySymbol',
                ],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getLoyaltyPrograms'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderCancel'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCancelReason',
                    'getCancelReasonCode',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItem',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgram' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgram',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgram'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getAccount',
                    'getProgram',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getCustomAttributes',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CustomAttributeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttribute' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CustomAttribute',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CustomAttribute'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getKey',
                    'getValue',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TrackingNumberIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ITrackingNumber' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TrackingNumber',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TrackingNumber'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getTrackingNumber',
                    'getUrl',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $personNameParams),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PaymentIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\Payment',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderAcceptedPaymentIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PaymentIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\Payment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getDescription',
                    'getTenderType',
                    'getMaskedAccount',
                    'getAmount',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TaxDescriptionIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ITaxDescription' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TaxDescription',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TaxDescription'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getDescription',
                    'getAmount',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItem',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AcceptedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getShipmentMethod', 'getShipmentMethodDisplayText'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getDestination'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShippedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($orderEventItemParams, ['getShippedQuantity']),
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getTrackingNumbers'
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ITrackingNumberIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TrackingNumberIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CancelledOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getName',
                    'getQuantity',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItem'
            ]
        ]
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getReturnUrl',
                    'getCancelUrl',
                    'getLocaleCode',
                    'getAmount',
                    'getAddressOverride',
                    'getCurrencyCode',
                ]
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $shippingAddressParams
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $iLineItemContainerParams
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $iLineItemIterableChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode',
                ]
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getPayerFirstName',
                    'getPayerLastName',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getToken',
                    'getCurrencyCode',
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $shippingAddressParams,
                    [
                        'getRequestId',
                        'getOrderId',
                        'getPayerId',
                        'getAmount',
                    ]
                )
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $shippingAddressParams,
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => $iLineItemContainerParams
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $iLineItemIterableChildPayloads
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getResponseCode',
                    'getTransactionId',
                    'getOrderId',
                    'getPaymentStatus',
                    'getPendingReason',
                    'getReasonCode',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getRequestId',
                    'getOrderId',
                    'getCurrencyCode',
                    'getAmount'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode',
                    'getPaymentStatus',
                    'getPendingReason',
                    'getReasonCode'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoVoidRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getRequestId',
                    'getOrderId',
                    'getCurrencyCode'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoVoidReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getOrderId',
                    'getResponseCode'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderRejected'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomerOrderId', 'getStoreId', 'getOrderCreateTimestamp'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderBackorder'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCurrencyCode',
                    'getCurrencySymbol',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getShipGroups',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PricedOrderItem',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroupIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderShipGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IEddMessage' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\EddMessage',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PricedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderEventItemParams,
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\EddMessage'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderCreditIssued'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getReturnOrCredit',
                    'getReferenceNumber',
                    'getTotalCredit'
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getOrderItems'
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CreditOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CreditOrderItem'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CreditOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CreditOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($orderEventItemParams, ['getRemainingQuantity']),
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmed'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getCurrencyCode',
                    'getCurrencySymbol',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getShipGroups',
                    'getPayments',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\MailingAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\StoreFrontDetails',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PricedOrderItem',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroupIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedShipGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedPaymentIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getShipmentMethod',
                    'getShippingDestination',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getShippingDestination',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getShipmentMethod',
                    'getShippingDestination',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getShippingDestination',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IEddMessage' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\EddMessage',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderConfirmedPaymentIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PaymentIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderPriceAdjustment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                    'getTotalAmount',
                    'getTaxAmount',
                    'getSubtotalAmount',
                    'getDutyAmount',
                    'getFeesAmount',
                    'getDiscountAmount',
                    'getShippedAmount',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getPerformedAdjustments',
                ],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPerformedAdjustmentIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustmentIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IAdjustedOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItemIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustmentIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPerformedAdjustment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustment',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\PerformedAdjustment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getType',
                    'getDisplay'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IAdjustedOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItem',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\AdjustedOrderItem'] =[
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getLineNumber', 'getItemId', 'getTitle', 'getAdjustments'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IItemPriceAdjustmentIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustmentIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustmentIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => []
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IItemPriceAdjustment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustment',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ItemPriceAdjustment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getModificationType',
                    'getAdjustmentCategory',
                    'getIsCredit',
                    'getAmount'
                ]
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderGiftCardActivation'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrencyCode',
                    'getCurrencySymbol',
                    'getCustomerFirstName',
                    'getCustomerLastName',
                    'getStoreId',
                    'getCustomerOrderId',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getGiftCardActivations'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getLoyaltyPrograms'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\GiftCardActivationIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivation' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\GiftCardActivation',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\GiftCardActivationIterable'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IGiftCardActivation' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\GiftCardActivation',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\GiftCardActivation'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderCreateReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getStatus',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderCreateRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getRequestId',
                    'getOrderId',
                    'getCreateTime',
                    'getBillingAddress',
                    'getCurrency',
                    'getLocale',
                    'getOrderTotal',
                    'getHostname',
                    'getIpAddress',
                    'getSessionId',
                    'getUserAgent',
                    'getJavascriptData',
                    'getReferrer',
                    'getContentTypes',
                    'getEncoding',
                    'getLanguage',
                ]
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getAssociateName', 'getAssociateNumber', 'getAssociateStore']
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getOrderSource', 'getOrderSourceType']
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
                    'getShipGroups',
                    'getDestinations',
                ],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getLoyaltyPrograms',
                    'getPayments',
                    'getItemRelationships',
                    'getHolds',
                    'getCustomAttributes',
                    'getTemplates',
                    'getOrderContextCustomAttributes',
                ],
            ],
            [
                'validator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\Order\OrderCreateReferences',
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IShipGroupIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\ShipGroupIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderDestinationIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPaymentIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PaymentIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IItemRelationshipIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\ItemRelationshipIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderHoldIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderHoldIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITemplateIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\TemplateIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Checkout\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Checkout\InvoiceTextCode'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCode'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Checkout\InvoiceTextCodeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IInvoiceTextCode' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\InvoiceTextCode',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Checkout\Tax'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $taxDataParams,
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getInvoiceTextCodes'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IInvoiceTextCodeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\InvoiceTextCodeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\CreditCardPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $orderPaymentContextParams,
                    [
                        'getResponseCode',
                        'getBankAuthorizationCode',
                        'getCvv2ResponseCode',
                        'getAvsResponseCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttribute'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getKey', 'getValue'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttribute' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttribute',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\Customization'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomizedItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomizationInstructions', 'getExtendedPrice'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomizationInstructionIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomizationInstructionIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomizationInstruction'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $customAttributeParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomizationInstructionIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomizationInstruction' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomizationInstruction',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomizationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomization' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\Customization',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\Discount'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getAmount'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITaxIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\TaxIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\DiscountIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IDiscount' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\Discount'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\EmailAddressDestination'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getEmailAddress'],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getFirstName', 'getLastName'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\Fee'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType', 'getAmount', 'getItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITaxIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\TaxIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITax' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\Tax',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\FeeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IFee' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\Fee',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\ItemRelationship'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getParentItemId', 'getType'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getItemReferences'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemReferenceIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemReferenceIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\ItemRelationshipIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IItemRelationship' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\ItemRelationship',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\LoyaltyProgram'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getAccount', 'getProgram'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\LoyaltyProgramIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ILoyaltyProgram' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\LoyaltyProgram',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $physicalAddressParams,
                    ['getId']
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderDestinationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IMailingAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\MailingAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IStoreLocation' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\StoreLocation',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IEmailAddressDestination' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\EmailAddressDestination',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderHold'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType', 'getHoldDate', 'getReason', 'getStatusDescription'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderHoldIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderHold' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderHold'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getId',
                    'getLineNumber',
                    'getItemId',
                    'getQuantity',
                ],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getEstimatedDeliveryWindowFrom', 'getEstimatedDeliveryWindowTo']
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getEstimatedShippingWindowFrom', 'getEstimatedShippingWindowTo']
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getStoreFrontDetails',
                    'getProxyPickupDetails',
                    'getGiftPricing',
                    'getCustomizationBasePrice',
                    'getCustomizations',
                    'getCustomAttributes',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IStoreFrontDetails' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\StoreFrontDetails',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IProxyPickupDetails' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\ProxyPickupDetails',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IFeeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\FeeIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomizationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomizationIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItem'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemReference'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getReferencedItemId'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemReferenceIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemReference' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemReference'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\PaymentIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICreditCardPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CreditCardPayment',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPrepaidCreditCardPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PrepaidCreditCardPayment',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPointsPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PointsPayment',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IStoredValueCardPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\StoredValueCardPayment',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPayPalPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PayPalPayment',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPrepaidCashOnDeliveryPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PrepaidCashOnDeliveryPayment',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IReservationPayment' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\ReservationPayment',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\PayPalPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $orderPaymentContextParams,
                    [
                        'getAmount',
                        'getAmountAuthorized',
                        'getAuthorizationResponseCode',
                    ]
                ),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\PointsPayment'] = [
        'validators' => $prepaidPaymentValidators,
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\PrepaidCashOnDeliveryPayment'] = [
        'validators' => $prepaidPaymentValidators,
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\PrepaidCreditCardPayment'] = [
        'validators' => $prepaidPaymentValidators,
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\PriceGroup'] = [
        'validators' => [
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes', 'getDiscounts'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IDiscountIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\DiscountIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITaxIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\TaxIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\ProxyPickupDetails'] = [
        'validators' => [
            [
                'validator' => $optionalGroupValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\ReservationPayment'] = [
        'validators' => [],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\ShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getChargeType', 'getId'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItemReferences'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getGiftPricing'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemReferenceIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItemReferenceIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\PriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\ShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\IShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\ShipGroup'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\StoredValueCardPayment'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderPaymentContextParams,
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getCustomAttributes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\StoreFrontDetails'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\StoreLocation'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $physicalAddressParams,
                    ['getId']
                ),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\Tax'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $taxDataParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\TaxIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITax' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\Tax'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\Template'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($customAttributeParams, ['getId']),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\TemplateIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITemplate' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Order\Template'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Address\ValidationReply'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $addressValidationHeaderParams),
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getSuggestedAddresses', 'getErrorLocations'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Address\ISuggestedAddressIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Address\SuggestedAddressIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Address\IErrorLocationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Address\ErrorLocationIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Address\ValidationRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $addressValidationHeaderParams),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Address\SuggestedAddressIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Address\ISuggestedAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Address\SuggestedAddress',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Address\SuggestedAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getErrorLocations'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Address\IErrorLocationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Address\ErrorLocationIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Address\ErrorLocationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Address\IErrorLocation' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Address\ErrorLocation',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Address\ErrorLocation'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getFieldName'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getCurrency',
                    'getBillingInformation',
                ]
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getShipGroups',
                    'getDestinations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IShipGroupIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ShipGroupIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ShipGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getChargeType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItems'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getGiftPricing'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IOrderItemRequestIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequestIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequestIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequest',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMailingAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\MailingAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IEmailAddressDestination' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\EmailAddressDestination',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\MailingAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge($physicalAddressParams, $personNameParams),
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\EmailAddressDestination'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getEmailAddress'],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getFirstName', 'getLastName'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\OriginPhysicalAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams,
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\OrderItemRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getLineNumber',
                    'getItemId',
                    'getQuantity',
                    'getAdminOrigin',
                    'getShippingOrigin',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getGiftPricing',
                    'getCustomizationBasePricing',
                    'getCustomizations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IOriginPhysicalAddress' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\OriginPhysicalAddress',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IDutyPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DutyPriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IFeeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\FeeIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ICustomizationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\CustomizationIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ICustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\CustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\FeeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IFee' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\Fee',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\Fee'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\CustomizationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ICustomization' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\Customization',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\Discount'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getAmount'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DiscountIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IDiscount' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\Discount'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\EmailAddressDestination'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getEmailAddress'],
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => ['getFirstName', 'getLastName'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup'] = [
        'validators' => [
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getDiscounts'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IDiscountIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DiscountIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DutyPriceGroup'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\PriceGroup'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteReply'] = [
        'validators' => [
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getShipGroups',
                    'getDestinations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedShipGroupIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroupIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\DestinationIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroupIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getChargeType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getItems'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getGiftPricing'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getLineNumber',
                    'getItemId',
                    'getQuantity',
                ],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getMerchandisePricing'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => [
                    'getShippingPricing',
                    'getDutyPricing',
                    'getFees',
                    'getGiftPricing',
                    'getCustomizationBasePricing',
                    'getCustomizations',
                ],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDutyPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedDutyPriceGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedFeeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedFeeIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedCustomizationIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomizationIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedCustomAttributeIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomAttributeIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup'] = [
        'validators' => [
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getDiscounts', 'getTaxes'],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDiscountIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscountIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedDutyPriceGroup'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItemIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedOrderItem',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedFeeIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedFee' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedFee',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomizationIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedCustomization' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomization',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\ITax' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\Tax',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscountIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDiscount' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscount'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedDiscount'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getAmount'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getTaxes'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxIterable'
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedFee'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getType'],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => ['getCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedPriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedPriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\Customization'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getUpCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\MerchandisePriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedCustomization'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getItemId'],
            ],
            [
                'validator' => $optionalSubpayloadValidator,
                'params' => ['getUpCharge'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxedMerchandisePriceGroup',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderCancelRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getCustomerOrderId', 'getReasonCode'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderCancelResponse'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getResponseStatus'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSummaryRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Customer\IOrderSearch' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSearch',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSearch'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSummaryResponse'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' =>  [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Customer\IOrderSummaryIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSummaryIterable',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSummaryIterable'] = [
        'validators' => [
            [
                'validator' => $iterableValidator,
                'params' => [],
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Customer\IOrderSummary' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSummary',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Customer\OrderSummary'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => ['getId', 'getModifiedTime', 'getOrderDate', 'getOrderTotal'],
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads,
    ];
    return $map;
});
