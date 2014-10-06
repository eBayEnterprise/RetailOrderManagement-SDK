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
        'creditcard/auth/VC' => [
            'request' => [
                'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest',
                'validators' => [
                    [
                        'validator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields',
                        'params' => ['']
                    ]
                ],
                'validatorIterator' => '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator',
                'schemaValidator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator'
            ],
            'reply' => [
                'payload' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply',
                'validators' => [
                    [
                        'validator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\RequiredFields',
                        'params' => ['']
                    ]
                ],
                'validatorIterator' => '\eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator',
                'schemaValidator' => '\eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator'
            ]
        ]
    ]
];