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

use eBayEnterprise\RetailOrderManagement\Api\IConfig;
use eBayEnterprise\RetailOrderManagement\Payload\Exception\UnsupportedPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class BidirectionalMessageFactory implements IBidirectionalMessageFactory
{
    /** @var  IConfig */
    protected $config;
    /** @var array maps a config key to a payload object */
    protected $messageTypeMap;
    /** @var IPayloadFactory */
    protected $payloadFactory;
    /** @var LoggerInterface */
    protected $logger;

    public function __construct(IConfig $config, IPayloadFactory $payloadFactory = null, array $messageMapping = [], LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->config = $config;
        $this->messageTypeMap = $messageMapping ?: require('BidirectionalMessageConfigMap.php');
        $this->payloadFactory = $payloadFactory ?: new PayloadFactory();
    }

    public function requestPayload()
    {
        return $this->messagePayload('request');
    }

    /**
     * Use the IConfig's getConfigKey to get a pair of request/reply payloads.
     * Type will specify if the request or reply payload should be retrieved.
     * @param string $type
     * @return IPayload
     * @throws UnsupportedPayload
     */
    public function messagePayload($type)
    {
        $key = $this->config->getConfigKey();
        if (isset($this->messageTypeMap[$key])) {
            return $this->payloadFactory->buildPayload($this->messageTypeMap[$key][$type], null, null, $this->logger);
        }
        throw new UnsupportedPayload("No payload found for '$key'");
    }

    public function replyPayload()
    {
        return $this->messagePayload('reply');
    }
}
