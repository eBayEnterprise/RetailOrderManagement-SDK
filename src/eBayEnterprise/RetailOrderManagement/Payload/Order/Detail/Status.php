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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use DateTime;

class Status implements IStatus
{
    use TPayload;

    /** @var string */
    protected $quantity;
    /** @var string */
    protected $status;
    /** @var DateTime */
    protected $statusDate;
    /** @var DateTime */
    protected $expectedShipmentDate;
    /** @var DateTime */
    protected $expectedDeliveryDate;
    /** @var DateTime */
    protected $productAvailabilityDate;
    /** @var string */
    protected $warehouse;

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
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = $this->getNewPayloadFactory();

        $this->initExtractPaths()
            ->initOptionalExtractPaths()
            ->initDatetimeExtractPaths();
    }

    /**
     * Initialize the protected class property array self::extractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initExtractPaths()
    {
        $this->extractionPaths = [
            'quantity' => 'string(x:Quantity)',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::optionalExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initOptionalExtractPaths()
    {
        $this->optionalExtractionPaths = [
            'status' => 'x:Status',
            'warehouse' => 'x:Warehouse',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::datetimeExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initDatetimeExtractPaths()
    {
        $this->datetimeExtractionPaths = [
            'statusDate' => 'string(x:StatusDate)',
            'expectedShipmentDate' => 'string(x:ExpectedShipmentDate)',
            'expectedDeliveryDate' => 'string(x:ExpectedDeliveryDate)',
            'productAvailabilityDate' => 'string(x:ProductAvailabilityDate)',
        ];
        return $this;
    }

    /**
     * @see IStatus::getQuantity()
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @see IStatus::setQuantity()
     * @codeCoverageIgnore
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     *  @see IStatus::getStatus()
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @see IStatus::setStatus()
     * @codeCoverageIgnore
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @see IStatus::getStatusDate()
     */
    public function getStatusDate()
    {
        return $this->statusDate;
    }

    /**
     * @see IStatus::setStatusDate()
     * @codeCoverageIgnore
     */
    public function setStatusDate(DateTime $statusDate)
    {
        $this->statusDate = $statusDate;
        return $this;
    }

    /**
     * @see IStatus::getExpectedShipmentDate()
     */
    public function getExpectedShipmentDate()
    {
        return $this->expectedShipmentDate;
    }

    /**
     * @see IStatus::setExpectedShipmentDate()
     * @codeCoverageIgnore
     */
    public function setExpectedShipmentDate(DateTime $expectedShipmentDate)
    {
        $this->expectedShipmentDate = $expectedShipmentDate;
        return $this;
    }

    /**
     * @see IStatus::getExpectedDeliveryDate()
     */
    public function getExpectedDeliveryDate()
    {
        return $this->expectedDeliveryDate;
    }

    /**
     * @see IStatus::setExpectedDeliveryDate()
     * @codeCoverageIgnore
     */
    public function setExpectedDeliveryDate(DateTime $expectedDeliveryDate)
    {
        $this->expectedDeliveryDate = $expectedDeliveryDate;
        return $this;
    }

    /**
     * @see IStatus::getProductAvailabilityDate()
     */
    public function getProductAvailabilityDate()
    {
        return $this->productAvailabilityDate;
    }

    /**
     * @see IStatus::setProductAvailabilityDate()
     * @codeCoverageIgnore
     */
    public function setProductAvailabilityDate(DateTime $productAvailabilityDate)
    {
        $this->productAvailabilityDate = $productAvailabilityDate;
        return $this;
    }

    /**
     * @see IStatus::getWarehouse()
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * @see IStatus::setWarehouse()
     * @codeCoverageIgnore
     */
    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeRequiredValue('Quantity', $this->xmlEncode($this->getQuantity()))
            . $this->serializeOptionalXmlEncodedValue('Status', $this->getStatus())
            . $this->serializeOptionalDateValue('StatusDate', 'c', $this->getStatusDate())
            . $this->serializeOptionalDateValue('ExpectedShipmentDate', 'Y-m-d', $this->getExpectedShipmentDate())
            . $this->serializeOptionalDateValue('ExpectedDeliveryDate', 'Y-m-d', $this->getExpectedDeliveryDate())
            . $this->serializeOptionalDateValue('ProductAvailabilityDate', 'Y-m-d', $this->getProductAvailabilityDate())
            . $this->serializeOptionalXmlEncodedValue('Warehouse', $this->getWarehouse());
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
}
