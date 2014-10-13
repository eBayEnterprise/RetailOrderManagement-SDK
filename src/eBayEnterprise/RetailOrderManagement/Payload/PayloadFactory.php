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

/**
 * Class PayloadFactory
 * @package eBayEnterprise\RetailOrderManagement\Payload
 */
class PayloadFactory implements IPayloadFactory
{
    /** @var  IConfig */
    protected $config;
    /** @var  IPayload */
    protected $requestPayload;
    /** @var  IPayload */
    protected $replyPayload;
    /** @var array maps a config key to a payload object */
    protected $payloadTypeMap;

    public function __construct(IConfig $config)
    {
        $this->config = $config;
        $this->payloadTypeMap = require('PayloadConfigMap.php');
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
     * Use the IConfig's key to build the right payload object.
     *
     * @param $type
     * @return IPayload
     * @throws UnsupportedOperation
     */
    protected function buildPayload($type)
    {
        $key = $this->config->getConfigKey();
        if (isset($this->payloadTypeMap[$key])) {
            $payloadTypes = $this->payloadTypeMap[$key];
            $payloadType = $payloadTypes[$type];
            $payloadClass = $payloadType['payload'];
            $validators = $payloadType['validators'];
            $validatorArray = $this->buildValidators($validators);

            $iterator = new $payloadType['validatorIterator']($validatorArray);
            $schemaValidator = new $payloadType['schemaValidator']();

            return new $payloadClass($iterator, $schemaValidator);
        }
        throw new UnsupportedOperation("No configuration found for '$key'");
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
