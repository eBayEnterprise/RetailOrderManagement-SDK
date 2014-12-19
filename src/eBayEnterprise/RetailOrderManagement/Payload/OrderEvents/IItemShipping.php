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

interface IItemShipping
{
    const MAILING_ADDRESS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress';
    const STORE_FRONT_DETAILS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails';

    /**
     * Shipping method used to deliver the item.
     *
     * @return string
     */
    public function getShipmentMethod();
    /**
     * @param string
     * @return self
     */
    public function setShipmentMethod($shipmentMethod);
    /**
     * Text name associated with the shipping method. Used in confirmation
     * emails and on webstore checkout pages.
     *
     * @return string
     */
    public function getShipmentMethodDisplayText();
    /**
     * @param string
     * @return self
     */
    public function setShipmentMethodDisplayText($shipmentMethodDisplayText);
    /**
     * Expected ship date.
     *
     * @return \DateTime
     */
    public function getEstimatedShipDate();
    /**
     * @param \DateTime
     * @return self
     */
    public function setEstimatedShipDate(DateTime $estimatedShipDate);
    /**
     * Destination the item is to be shipped to. May be a customer mailing
     * address or a store front address.
     *
     * @return IDestination
     */
    public function getDestination();
    /**
     * @param IDestination
     * @return self
     */
    public function setDestination(IDestination $destination);
}
