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

use DateTime;

trait TItemShipping
{
    /** @var string */
    protected $shipmentMethod;
    /** @var string */
    protected $shipmentMethodDisplayText;
    /** @var DateTime */
    protected $estimatedShipDate;
    /** @var IDestination */
    protected $destination;

    public function getShipmentMethod()
    {
        return $this->shipmentMethod;
    }

    public function setShipmentMethod($shipmentMethod)
    {
        $this->shipmentMethod = $shipmentMethod;
        return $this;
    }

    public function getShipmentMethodDisplayText()
    {
        return $this->shipmentMethodDisplayText;
    }

    public function setShipmentMethodDisplayText($shipmentMethodDisplayText)
    {
        $this->shipmentMethodDisplayText = $shipmentMethodDisplayText;
        return $this;
    }

    public function getEstimatedShipDate()
    {
        return $this->estimatedShipDate;
    }

    public function setEstimatedShipDate(DateTime $estimatedShipDate)
    {
        $this->estimatedShipDate = $estimatedShipDate;
        return $this;
    }

    public function getDestination()
    {
        return $this->destination;
    }

    public function setDestination(IDestination $destination)
    {
        $this->destination = $destination;
        return $this;
    }

    /**
     * Serialize the item shipping data.
     *
     * @return string
     */
    protected function serializeItemShipping()
    {
        return sprintf(
            '<Shipping><ShipmentMethod displayText="%s">%s</ShipmentMethod>%s%s</Shipping>',
            $this->getShipmentMethodDisplayText(),
            $this->getShipmentMethod(),
            $this->serializeEstimatedShipDate(),
            $this->serializeDestination()
        );
    }

    /**
     * Return the serialized destination if there is one. An empty string otherwise.
     *
     * @return string
     */
    protected function serializeDestination()
    {
        $destination = $this->getDestination();
        return $destination ? $destination->serialize() : '';
    }

    /**
     * Return the serialized ship date if there is one, empty string otherwise.
     *
     * @return string
     */
    protected function serializeEstimatedShipDate()
    {
        $shipDate = $this->getEstimatedShipDate();
        return $shipDate
            ? sprintf('<EstimatedShipDate>%s</EstimatedShipDate>', $shipDate->format('Y-m-d'))
            : '';
    }
}
