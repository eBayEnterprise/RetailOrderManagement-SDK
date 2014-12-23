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

trait TShipGroup
{
    /** @var IDestination */
    protected $destination;
    /** @var string */
    protected $shipmentMethod;
    /** @var string */
    protected $shipmentMethodDisplayText;

    public function getShippingDestination()
    {
        return $this->destination;
    }

    public function setShippingDestination(IDestination $destination)
    {
        $this->destination = $destination;
        return $this;
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

    /**
     * Text string to display for the shipment method
     *
     * @return string
     */
    public function getShipmentMethodDisplayText()
    {
        return $this->shipmentMethodDisplayText;
    }

    /**
     * @param string
     * @return self
     */
    public function setShipmentMethodDisplayText($displayText)
    {
        $this->shipmentMethodDisplayText = $displayText;
        return $this;
    }

    /**
     * Get a payload instance mapped to the specified interface
     * @param  string   $interface
     * @return IPayload
     */
    abstract protected function buildPayloadForInterface($interface);

    /**
     * Deserialize data not mapped by the extraction paths
     * @return self
     */
    abstract protected function deserializeExtra($serializedPayload);

    /**
     * Deserialize the destination
     * @param  DOMXPath $xpath
     * @return self
     */
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

    protected function serializeShipmentMethod()
    {
        $serializedAttribute = $this->getShipmentMethodDisplayText() ?
            " displayText=\"{$this->getShipmentMethodDisplayText()}\"" :
            '';
        return sprintf(
            '<ShipmentMethod%s>%s</ShipmentMethod>',
            $serializedAttribute,
            $this->getShipmentMethod()
        );
    }
}
