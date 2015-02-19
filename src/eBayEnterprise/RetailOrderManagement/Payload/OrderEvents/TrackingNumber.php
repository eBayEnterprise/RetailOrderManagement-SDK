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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TrackingNumber implements ITrackingNumber
{
    use TPayload;

    /** @var string */
    protected $number;
    /** @var string */
    protected $url;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator unused, kept to allow parent payload to be passed
     * @param IPayloadMap unused, kept to allow parent payload to be passed
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'number' => 'string(@trackingNumber)',
            'url' => 'string(@trackingURL)',
        ];
    }

    public function getTrackingNumber()
    {
        return $this->number;
    }

    public function setTrackingNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [
            'trackingNumber' => $this->getTrackingNumber(),
            'trackingURL' => $this->getUrl(),
        ];
    }

    /**
     * Tracking info nodes have no content, just the two attributes on the root node.
     *
     * @return string
     */
    protected function serializeContents()
    {
        return '';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
