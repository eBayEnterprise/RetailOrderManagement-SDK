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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IShipGroup extends IPayload, IOrderItemContainer
{
    const ROOT_NODE = 'ShipGroup';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const MAILING_ADDRESS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress';
    const STORE_FRONT_DETAILS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails';

    /**
     * Get the destination the items were shipped to. May be a customer
     * mailing address or store front address if shipped to a store.
     *
     * @return IDestination
     */
    public function getShippingDestination();
    /**
     * @param IDestination
     * @return self
     */
    public function setShippingDestination(IDestination $shippingDestination);

    /**
     * This text string represents the type of shipping used to deliver the order, for example, USPS. These values are unique to each webstore.
     *
     * @return string
     */
    public function getShipmentMethod();

    /**
     * @param string
     * @return self
     */
    public function setShipmentMethod($method);

    /**
     * Get the string to display for the shipment method
     *
     * @return string
     */
    public function getShipmentMethodDisplayText();

    /**
     * @param string
     * @return self
     */
    public function setShipmentMethodDisplayText($displayText);
}
