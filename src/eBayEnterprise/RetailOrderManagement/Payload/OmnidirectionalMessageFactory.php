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

class OmnidirectionalMessageFactory implements IMessageFactory
{
    /** @var array */
    protected $messageTypeMap;
    /** @var IPayloadFactory */
    protected $payloadFactory;
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param IConfig $config
     * @param IPayloadFactory $payloadFactory
     * @param array $messageMapping key/value pairs of config key => payload type
     */
    public function __construct(IPayloadFactory $payloadFactory = null, array $messageMapping = [], LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->payloadFactory = $payloadFactory ?: new PayloadFactory();
        $this->messageTypeMap = $messageMapping ?: require('OmnidirectionalMessageConfigMap.php');
    }

    public function messagePayload($type)
    {
        if (isset($this->messageTypeMap[$type])) {
            return $this->payloadFactory->buildPayload($this->messageTypeMap[$type], null, null, $this->logger);
        }
        throw new UnsupportedPayload("No payload found for '$type'");
    }
}
