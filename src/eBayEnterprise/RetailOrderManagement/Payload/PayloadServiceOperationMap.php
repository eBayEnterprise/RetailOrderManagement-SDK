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

return [
    'payments' => [
        'creditcard/auth' => [
            'request' => [
                'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest',
                'validators' => [
                    [
                        'validator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields',
                        'params' => [
                            'getRequestId',
                            'getOrderId',
                            'getPanIsToken',
                            'getCardNumber',
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
                    ],
                    [
                        'validator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\OptionalGroup',
                        'params' => [
                            'getAuthenticationAvailable',
                            'getAuthenticationStatus',
                            'getCavvUcaf',
                            'getTransactionId',
                            'getPayerAuthenticationResponse',
                        ]
                    ],
                ],
                'validatorIterator' => '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator',
                'schemaValidator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator'
            ],
            'reply' => [
                'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply',
                'validators' => [
                    [
                        'validator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields',
                        'params' => [
                            'getOrderId',
                            'getPaymentAccountUniqueId',
                            'getPanIsToken',
                            'getAuthorizationResponseCode',
                            'getBankAuthorizationCode',
                            'getCvv2ResponseCode',
                            'getAvsResponseCode',
                            'getAmountAuthorized',
                            'getCurrencyCode',
                        ]
                    ],
                ],
                'validatorIterator' => '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator',
                'schemaValidator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator'
            ]
        ],
    ],
];
