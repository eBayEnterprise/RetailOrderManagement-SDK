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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderCreateReply implements IOrderCreateReply
{
    use TTopLevelPayload;

    const ROOT_NODE = 'OrderCreateResponse';

    /** @var string */
    protected $status;
    /** @var string */
    protected $description;

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
        $this->schemaValidator = $schemaValidator;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'status' => 'string(x:ResponseStatus)'
        ];
        $this->optionalExtractionPaths = [
            'description' => 'x:ResponseDescription',
        ];
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if ($status === self::STATUS_FAIL ||
            $status === self::STATUS_SUCCESS ||
            $status === self::STATUS_TIMEOUT
        ) {
            $this->status = $status;
        }
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Check if the order create was a success.
     *
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getStatus() === self::STATUS_SUCCESS;
    }

    /**
     * Check if the order create resulted in a timeout.
     *
     * @return bool
     */
    public function isTimeout()
    {
        return $this->getStatus() === self::STATUS_TIMEOUT;
    }

    /**
     * Check if the order create was a failure.
     *
     * @return bool
     */
    public function isFail()
    {
        return $this->getStatus() === self::STATUS_FAIL;
    }

    protected function serializeContents()
    {
        return $this->serializeStatus() . $this->serializeDescription();
    }

    protected function serializeStatus()
    {
        return "<ResponseStatus>{$this->xmlEncode($this->getStatus())}</ResponseStatus>";
    }

    protected function serializeDescription()
    {
        $description = $this->getDescription();
        return $description ? "<ResponseDescription>{$this->xmlEncode($description)}</ResponseDescription>" : '';
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
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
