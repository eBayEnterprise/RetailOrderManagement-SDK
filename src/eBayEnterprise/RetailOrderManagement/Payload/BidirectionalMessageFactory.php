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

class BidirectionalMessageFactory implements IBidirectionalMessageFactory
{
    /** @var  IConfig */
    protected $config;
    /** @var array maps a config key to a payload object */
    protected $messageTypeMap;
    /** @var IPayloadFactory */
    protected $payloadFactory;

    public function __construct(IConfig $config, IPayloadFactory $payloadFactory = null, array $messageMapping = [])
    {
        $this->config = $config;
        $this->messageTypeMap = $messageMapping ?: require('MessageConfigMap.php');
        $this->payloadFactory = $payloadFactory ?: new PayloadFactory();
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
        if (isset($this->messageTypeMap[$key])) {
            return $this->payloadFactory->buildPayload($this->messageTypeMap[$key][$type]);
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
