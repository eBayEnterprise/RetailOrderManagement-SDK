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
    $paymentAccountUniqueIdParams = ['getCardNumber', 'getPanIsToken'];
    $paymentContextParams = array_merge($paymentAccountUniqueIdParams, ['getOrderId']);
    $validatorIterator = '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator';
    $xsdSchemaValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator';
    $xmlValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XmlValidator';
    $requiredFieldsValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields';
    $optionalGroupValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\OptionalGroup';
    $subpayloadValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\Subpayloads';
    $optionalSubpayloadValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\OptionalSubpayloads';
    $iterableValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\IterablePayload';
    $payloadMap = '\eBayEnterprise\RetailOrderManagement\Payload\PayloadMap';
    $shippingAddressParams = ['getShipToLines', 'getShipToCity', 'getShipToCountryCode'];
    $physicalAddressParams = ['getLines', 'getCity', 'getCountryCode'];
    $personNameParams = ['getLastName', 'getFirstName'];
    $orderItemParams = ['getLineNumber', 'getItemId', 'getQuantity', 'getTitle', 'getDescription'];
    $shippedItemParams = ['getShippedQuantity'];
    $creditItemParams = ['getRemainingQuantity'];
    $noChildPayloads = [
        'payloadMap' => $payloadMap,
        'types' => [],
    ];
    $iLineItemIterableChildPayloads = [
        'payloadMap' => $payloadMap,
        'types' => [
            '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable' =>
                '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'
        ]
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramIterable' =>
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramIterable' =>
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramIterable' =>
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgram' =>
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ICustomAttributeIterable' =>
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ICustomAttribute' =>
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
                'params' => array_merge($physicalAddressParams, ['getLocationId']),
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
                'params' => $orderItemParams,
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
                'params' => $orderItemParams,
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
                'params' => array_merge($orderItemParams, $shippedItemParams),
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
                'params' => $orderItemParams,
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
                'validator' => $optionalGroupValidator,
                'params' => [
                    'getLineItemsTotal',
                    'getShippingTotal',
                    'getTaxTotal',
                    'getCurrencyCode',
                ]
            ],
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgramIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItemIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderItemIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IOrderItem' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderItem',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroupIterable' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroupIterable',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShipGroup' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroup',
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IEddMessage' =>
                    '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\EddMessage',
            ],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderItemIterable'] =
        $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderItemIterable'];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\BackOrderItem'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $orderItemParams,
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
    $map['\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ShipGroup'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [],
            ],
            [
                'validator' => $subpayloadValidator,
                'params' => [
                    'getOrderItems',
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
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ILoyaltyProgramIterable' =>
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
                'params' => array_merge($orderItemParams, $creditItemParams),
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xmlValidator,
        'childPayloads' => $noChildPayloads
    ];
    return $map;
});
