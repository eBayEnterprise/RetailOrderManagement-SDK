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

class Shipment implements IShipment
{
    use TPayload, TShippedItemContainer;

    /** @var string */
    protected $id;
    /** @var string */
    protected $destinationRef;
    /** @var string */
    protected $warehouse;
    /** @var string */
    protected $carrier;
    /** @var string */
    protected $mode;
    /** @var string */
    protected $displayText;
    /** @var string */
    protected $totalWeight;
    /** @var DateTime */
    protected $shippedDate;

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
            ->initDatetimeExtractPaths()
            ->initSubPayloadExtractPaths()
            ->initSubPayloadProperties();
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
            'id' => 'string(@id)',
            'destinationRef' => 'string(@destinationRef)',
            'warehouse' => 'string(x:Warehouse)',
            'totalWeight' => 'string(x:TotalWeight)',
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
            'carrier' => 'x:Carrier',
            'mode' => 'x:Carrier/@mode',
            'displayText' => 'x:Carrier/@displayText',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::subpayloadExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initSubPayloadExtractPaths()
    {
        $this->subpayloadExtractionPaths = [
            'shippedItems' => 'x:ShippedItems',
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
            'shippedDate' => 'string(x:ShippedDate)',
        ];
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setShippedItems($this->buildPayloadForInterface(
            static::SHIPPED_ITEM_ITERABLE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IShipment::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see IShipment::setId()
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @see IShipment::getDestinationRef()
     */
    public function getDestinationRef()
    {
        return $this->destinationRef;
    }

    /**
     * @see IShipment::setDestinationRef()
     * @codeCoverageIgnore
     */
    public function setDestinationRef($destinationRef)
    {
        $this->destinationRef = $destinationRef;
        return $this;
    }

    /**
     * @see IShipment::getWarehouse()
     */
    public function getWarehouse()
    {
        return $this->warehouse;
    }

    /**
     * @see IShipment::setWarehouse()
     * @codeCoverageIgnore
     */
    public function setWarehouse($warehouse)
    {
        $this->warehouse = $warehouse;
        return $this;
    }

    /**
     * @see IShipment::getCarrier()
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @see IShipment::setCarrier()
     * @codeCoverageIgnore
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @see IShipment::getMode()
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @see IShipment::setMode()
     * @codeCoverageIgnore
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * @see IShipment::getDisplayText()
     */
    public function getDisplayText()
    {
        return $this->displayText;
    }

    /**
     * @see IShipment::setDisplayText()
     * @codeCoverageIgnore
     */
    public function setDisplayText($displayText)
    {
        $this->displayText = $displayText;
        return $this;
    }

    /**
     * @see IShipment::getTotalWeight()
     */
    public function getTotalWeight()
    {
        return $this->totalWeight;
    }

    /**
     * @see IShipment::setTotalWeight()
     * @codeCoverageIgnore
     */
    public function setTotalWeight($totalWeight)
    {
        $this->totalWeight = $totalWeight;
        return $this;
    }

    /**
     * @see IShipment::getShippedDate()
     */
    public function getShippedDate()
    {
        return $this->shippedDate;
    }

    /**
     * @see IShipment::setShippedDate()
     * @codeCoverageIgnore
     */
    public function setShippedDate(DateTime $shippedDate)
    {
        $this->shippedDate = $shippedDate;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->getShippedItems()->serialize()
            . $this->serializeRequiredValue('Warehouse', $this->xmlEncode($this->getWarehouse()))
            . $this->serializeCarrierValue('Carrier', $this->getCarrier())
            . $this->serializeRequiredValue('TotalWeight', $this->xmlEncode($this->getTotalWeight()))
            . $this->serializeOptionalDateValue('ShippedDate', 'c', $this->getShippedDate());
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

    /**
     * Serializing the carrier XML node.
     *
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeCarrierValue($nodeName, $value)
    {
        $modeAttribute = $this->serializeOptionalAttribute('mode', $this->xmlEncode($this->getMode()));
        $displayTextAttribute = $this->serializeOptionalAttribute('displayText', $this->xmlEncode($this->getDisplayText()));
        return $value
            ? sprintf('<%s %s %s>%s</%1$s>', $nodeName, $modeAttribute, $displayTextAttribute, $this->xmlEncode($value)) : null;
    }

   /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        return [
            'id' => $this->getId(),
            'destinationRef' => $this->getDestinationRef(),
        ];
    }
}
