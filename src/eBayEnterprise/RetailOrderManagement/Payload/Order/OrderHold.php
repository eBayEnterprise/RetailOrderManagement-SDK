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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderHold implements IOrderHold
{
    use TPayload;

    const ROOT_NODE = 'Hold';

    /** @var string */
    protected $type;
    /** @var DateTime */
    protected $holdDate;
    /** @var string */
    protected $reason;
    /** @var string */
    protected $resolverUserId;
    /** @var string */
    protected $statusDescription;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
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
            'type' => 'string(x:HoldType)',
            'reason' => 'string(x:ReasonText)',
            'statusDescription' => 'string(x:StatusDescription)',
        ];
        $this->optionalExtractionPaths = [
            'resolverUserId' => 'x:ResolverUserId',
        ];
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type;
        return $this;
    }

    public function getHoldDate()
    {
        return $this->holdDate;
    }

    public function setHoldDate(DateTime $holdDate)
    {
        $this->holdDate;
        return $this;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason)
    {
        $this->reason;
        return $this;
    }

    public function getResolverUserId()
    {
        return $this->resolverUserId;
    }

    public function setResolverUserId($resolverUserId)
    {
        $this->resolverUserId;
        return $this;
    }

    public function getStatusDescription()
    {
        return $this->statusDescription;
    }

    public function setStatusDescription($statusDescription)
    {
        $this->statusDescription;
        return $this;
    }

    protected function serializeContents()
    {
        return "<HoldType>{$this->xmlEncode($this->getType())}</HoldType>"
            . "<LastHoldTypeDate>{$this->getHoldDate()->format('c')}</LastHoldTypeDate>"
            . "<ReasonText>{$this->xmlEncode($this->getReason())}</ReasonText>"
            . $this->serializeOptionalXmlEncodedValue('ResolverUserId', $this->getResolverUserId())
            . "<StatusDescription>{$this->xmlEncode($this->getStatusDescription())}</StatusDescription>";
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $holdDateValue = $xpath->evaluate('string(x:LastHoldTypeDate)');
        $this->holdDate = $holdDateValue ? new DateTime($holdDateValue) : null;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
