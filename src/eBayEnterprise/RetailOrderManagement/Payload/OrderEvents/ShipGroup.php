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

use DOMXPath;
use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

class ShipGroup implements IShipGroup
{
    use TPayload, TOrderItemContainer;

    /** @var IPayloadMap */
    protected $payloadMap;
    /** @var PayloadFactory */
    protected $payloadFactory;
    /** @var IDestination */
    protected $destination;
    /** @var string */
    protected $shipmentMethod;
    /** @var DateTime */
    protected $estimatedShipDate;
    /** @var IEddMessage */
    protected $eddMessage;
    /** @var bool */
    protected $hasEddMessageNode;

    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap
    ) {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new PayloadFactory();

        $this->orderItems =
            $this->buildPayloadForInterface(static::ORDER_ITEM_ITERABLE_INTERFACE);
        $this->eddMessage =
            $this->buildPayloadForInterface(static::EDD_MESSAGE_INTERFACE);

        $this->extractionPaths = [
            'shipmentMethod' => 'string(x:ShipmentMethod)',
        ];
        $this->optionalExtractionPaths = [
            'estimatedShipDate' => 'x:EstimatedShipDate',
        ];
        $this->subpayloadExtractionPaths = [
            'orderItems' => 'x:BackorderOrderItems',
            'eddMessage' => 'x:EDDMessage',
        ];
        $this->hasEddMessageNode = false;
    }

    public function getShippingDestination()
    {
        return $this->destination;
    }

    public function setShippingDestination(IDestination $destination)
    {
        $this->destination = $destination;
        return $this;
    }

    protected function deserializeExtra($serializedPayload)
    {
        $this->setEstimatedShipDate($this->estimatedShipDate);
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $this->hasEddMessageNode = $this->hasEddMessage($xpath);
        return $this->deserializeShippingDestination($xpath);
    }

    protected function deserializeShippingDestination(DOMXPath $xpath)
    {
        $addressMap = [
            'ShippedAddress' => static::MAILING_ADDRESS_INTERFACE,
            'StoreFrontAddress' => static::STORE_FRONT_DETAILS_INTERFACE,
        ];
        $destination = null;
        $destinationNode = null;
        foreach ($addressMap as $type => $interface) {
            $node = $xpath->query("x:$type");
            if ($node->length) {
                $destinationNode = $node->item(0);
                $destination = $this->buildPayloadForInterface($interface);
                break;
            }
        }
        if ($destination && $destinationNode) {
            $destination->deserialize($destinationNode->C14N());
            $this->setShippingDestination($destination);
        }
        return $this;
    }

    /**
     * Checks if the payload xml has the EDDMessage xml node.
     * @param DOMXPath
     * @return bool true the 'EDDMessage' node exits in the xml payload otherwise false
     */
    protected function hasEddMessage(DOMXPath $xpath)
    {
        return ($xpath->query('x:EDDMessage')->length > 0);
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [];
    }

    protected function serializeContents()
    {
        return $this->getOrderItems()->serialize()
            . "<ShipmentMethod>{$this->getShipmentMethod()}</ShipmentMethod>"
            . $this->serializeEstimatedShipDate()
            . $this->serializeEddMessage()
            . $this->getShippingDestination()->serialize();
    }

    protected function serializeEddMessage()
    {
        return $this->hasEddMessageNode ? $this->getEddMessage()->serialize() : '';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function serializeEstimatedShipDate()
    {
        $date = $this->getEstimatedShipDate();
        return ($date instanceof DateTime)
            ? "<EstimatedShipDate>{$date->format('Y-m-d')}</EstimatedShipDate>" : '';
    }

    public function getShipmentMethod()
    {
        return $this->shipmentMethod;
    }

    public function setShipmentMethod($method)
    {
        $this->shipmentMethod = $method;
        return $this;
    }

    public function getEstimatedShipDate()
    {
        return $this->estimatedShipDate;
    }

    public function setEstimatedShipDate($date)
    {
        $this->estimatedShipDate =
            (!empty($date) && is_string($date)) ? new DateTime($date) : null;
        return $this;
    }

    public function getEddMessage()
    {
        return $this->eddMessage;
    }

    public function setEddMessage(IEddMessage $message)
    {
        $this->eddMessage = $message;
        return $this;
    }
}
