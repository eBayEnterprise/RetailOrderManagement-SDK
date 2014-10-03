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


namespace eBayEnterprise\RetailOrderManagement\Payload;

use eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedOperation;
use eBayEnterprise\RetailOrderManagement\Api\IConfig;

class PayloadFactory implements IPayloadFactory
{
    /** @var  IConfig */
    protected $config;
    /** @var  IPayload */
    protected $requestPayload;
    /** @var  IPayload */
    protected $replyPayload;
    /** @var array maps a service_operation pair to a payload object */
    protected $payloadTypeMap = [
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

    public function __construct(IConfig $config)
    {
        $this->config = $config;
    }

    /**
     * Instantiate each validator and store it in an array
     * to be passed to IValidatorIterator
     *
     * @param $validators
     * @return array
     */
    protected function buildValidators($validators)
    {
        $array = [];
        foreach ($validators as $validator) {
            $params = $validator['params'];
            $array[] = new $validator['validator']($params);
        }
        return $array;
    }

    /**
     * Use the service/operation tuple from the IConfig object
     * as a key into an array of information needed to build
     * the IPayload object
     *
     * @param $type
     * @return IPayload|UnsupportedOperation
     * @throws UnsupportedOperation
     */
    protected function buildPayload($type)
    {
        list($service, $operation) = $this->config->getServiceOperation();
        if (isset($this->payloadTypeMap[$service][$operation])) {
            $payloadTypes = $this->payloadTypeMap[$service][$operation];
            $payloadType = $payloadTypes[$type];
            $payloadClass = $payloadType['payload'];
            $validators = $payloadType['validators'];
            $validatorArray = $this->buildValidators($validators);

            $iterator = new $payloadType['validatorIterator']($validatorArray);
            $schemaValidator = new $payloadType['schemaValidator']();

            return new $payloadClass($iterator, $schemaValidator);
        }

        throw new UnsupportedOperation("$operation not supported on $service");
    }

    public function requestPayload()
    {
        return $this->buildPayload('request');
    }

    public function replyPayload()
    {
        return $this->buildPayload('reply');
    }
} 