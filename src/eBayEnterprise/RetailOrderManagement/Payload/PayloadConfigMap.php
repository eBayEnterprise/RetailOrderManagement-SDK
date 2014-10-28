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
    $paymentAccountUniqueIdParams = ['getCardNumber', 'getPanIsToken'];
    $paymentContextParams = array_merge($paymentAccountUniqueIdParams, ['getOrderId']);
    $map = [];
    $validatorIterator = '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator';
    $xsdSchemaValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator';
    $requiredFieldsValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields';
    $optionalGroupValidator = '\eBayEnterprise\RetailOrderManagement\Payload\Validator\OptionalGroup';
    $payloadMap = '\eBayEnterprise\RetailOrderManagement\Payload\PayloadMap';
    $shippingAddressParams = ['getShipToLines', 'getShipToCity', 'getShipToCountryCode'];
    $physicalAddressParams = ['getCity', 'getLines', 'getCountryCode'];
    $physicalAddressOptionalParams = ['getMainDivision', 'getPostalCode'];
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest'] = [
        'validators' =>
            $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest']['validators'],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [],
        ],
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
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => [
                    'getLineItemsTotal',
                    'getShippingTotal',
                    'getTaxTotal',
                    'getCurrencyCode',
                ]
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItem'
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $shippingAddressParams,
                    [
                        'getReturnUrl',
                        'getCancelUrl',
                        'getLocaleCode',
                        'getAmount',
                        'getAddressOverride',
                        'getCurrencyCode',
                        'getLineItems',
                    ]
                )
            ],
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'
            ]
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => ['\eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalAddress' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalAddress']
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutRequest'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => array_merge(
                    $shippingAddressParams,
                    [
                        'getLineItems',
                        'getRequestId',
                        'getOrderId',
                        'getPayerId',
                        'getAmount',
                        'getPickUpStoreId',
                        'getShipToName'
                    ]
                )
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => [
                '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'
            ]
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
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
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
    ];
    $map['\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalAddress'] = [
        'validators' => [
            [
                'validator' => $requiredFieldsValidator,
                'params' => $physicalAddressParams
            ],
            [
                'validator' => $optionalGroupValidator,
                'params' => array_merge(
                    $physicalAddressOptionalParams,
                    [
                        'getAddressStatus',
                    ]
                )
            ]
        ],
        'validatorIterator' => $validatorIterator,
        'schemaValidator' => $xsdSchemaValidator,
        'childPayloads' => [
            'payloadMap' => $payloadMap,
            'types' => []
        ]
    ];
    return $map;
});
