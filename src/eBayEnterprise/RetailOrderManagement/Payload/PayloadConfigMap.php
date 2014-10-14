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
    $map['payments/creditcard/auth'] = [
        'request' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest',
            'validators' => [
                [
                    'validator' => $requiredFieldsValidator,
                    'params' => array_merge(
                        $paymentContextParams,
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
                            'getShipToLines',
                            'getShipToCity',
                            'getShipToCountryCode',
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
            'schemaValidator' => $xsdSchemaValidator
        ],
        'reply' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply',
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
            'schemaValidator' => $xsdSchemaValidator
        ]
    ];
    $map['payments/storedvalue/balance'] = [
        'request' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceRequest',
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
            'schemaValidator' => $xsdSchemaValidator
        ],
        'reply' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceReply',
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
            'schemaValidator' => $xsdSchemaValidator
        ],
    ];
    $map['payments/storedvalue/redeem'] = [
        'request' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest',
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
            'schemaValidator' => $xsdSchemaValidator
        ],
        'reply' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemReply',
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
            'schemaValidator' => $xsdSchemaValidator
        ],
    ];
    $map['payments/storedvalue/redeemvoid'] = [
        'request' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest',
            'validators' => $map['payments/storedvalue/redeem']['request']['validators'],
            'validatorIterator' => $validatorIterator,
            'schemaValidator' => $xsdSchemaValidator
        ],
        'reply' => [
            'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidReply',
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
            'schemaValidator' => $xsdSchemaValidator
        ],
    ];
    return $map;
});
