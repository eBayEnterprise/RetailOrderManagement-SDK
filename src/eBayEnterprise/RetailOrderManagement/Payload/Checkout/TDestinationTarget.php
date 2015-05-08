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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

trait TDestinationTarget
{
    /** @var string */
    protected $destinationId;
    /** @var IDestination */
    protected $destination;

    /**
     * @return IDestination
     */
    public function getDestination()
    {
        if (!$this->destination && $this->destinationId) {
            $destinationContainer = $this->getDestinationContainer();
            if ($destinationContainer) {
                foreach ($destinationContainer->getDestinations() as $destination) {
                    if ($destination->getId() === $this->destinationId) {
                        $this->destination = $destination;
                        break;
                    }
                }
            }
        }
        return $this->destination;
    }

    /**
     * @return string
     */
    public function getDestinationId()
    {
        $destination = $this->getDestination();
        return $destination ? $destination->getId() : $this->destinationId;
    }

    public function setDestination(IDestination $destination)
    {
        $this->destination = $destination;
        $destinationContainer = $this->getDestinationContainer();
        if ($destinationContainer) {
            $destinationContainer->getDestinations()->offsetSet($destination);
        }
        return $this;
    }

    /**
     * Get the collection of destinations associated to the payload the ship
     * group belongs to.
     *
     * @return IDestinationIterable
     */
    protected function getDestinationContainer()
    {
        return $this->getAncestorPayloadOfType(static::DESTINATION_CONTAINER);
    }
}
