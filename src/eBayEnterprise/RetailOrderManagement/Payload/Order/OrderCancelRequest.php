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

class OrderCancelRequest implements IOrderCancelRequest
{
    use TTopLevelPayload;

    /** @var string */
    protected $orderType;
    /** @var string */
    protected $customerOrderId;
    /** @var string */
    protected $reasonCode;
    /** @var string */
    protected $reason;

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
            'customerOrderId' => 'string(x:CustomerOrderId)',
            'reasonCode' => 'string(x:ReasonCode)',
        ];
        $this->optionalExtractionPaths = [
            'orderType' => '@orderType',
            'reason' => 'x:Reason',
        ];
    }

    /**
     * @see IOrderCancelRequest::getOrderType()
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @see IOrderCancelRequest::setOrderType()
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * @see IOrderCancelRequest::getCustomerOrderId()
     */
    public function getCustomerOrderId()
    {
        return $this->customerOrderId;
    }

    /**
     * @see IOrderCancelRequest::setCustomerOrderId()
     */
    public function setCustomerOrderId($customerOrderId)
    {
        $this->customerOrderId = $customerOrderId;
        return $this;
    }

    /**
     * @see IOrderCancelRequest::getReasonCode()
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @see IOrderCancelRequest::setReasonCode()
     */
    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
        return $this;
    }

    /**
     * @see IOrderCancelRequest::getReason()
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * @see IOrderCancelRequest::setReason()
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeRequiredValue('CustomerOrderId', $this->xmlEncode($this->getCustomerOrderId()))
            . $this->serializeRequiredValue('ReasonCode', $this->xmlEncode($this->getReasonCode()))
            . $this->serializeOptionalXmlEncodedValue('Reason', $this->getReason());
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    /**
     * Validate the serialized data via the schema validator.
     * @param  string $serializedData
     * @return self
     */
    protected function schemaValidate($serializedData)
    {
        $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        return $this;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $orderType = $this->getOrderType();
        $attributes = ['xmlns' => $this->getXmlNamespace()];
        return $orderType
            ? array_merge($attributes, ['orderType' => $this->getOrderType()])
            : $attributes;
    }
}
