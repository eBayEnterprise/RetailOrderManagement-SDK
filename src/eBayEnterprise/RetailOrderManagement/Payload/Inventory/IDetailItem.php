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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use \DateTime;

/**
 * Inventory delivery details for an item which can be fulfilled.
 */
interface IDetailItem extends IItem, IAddress
{
    /**
     * The earliest date when the order line item is expected to arrive at the ship-to address.
     *
     * @return DateTime
     */
    public function getDeliveryWindowFromDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryWindowFromDate(DateTime $deliveryWindowFromDate);

    /**
     * The latest date when the order line item is expected to arrive at the ship-to address.
     *
     * @return DateTime
     */
    public function getDeliveryWindowToDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryWindowToDate(DateTime $deliveryWindowToDate);

    /**
     * The earliest date when the order line item is expected to leave the fulfillment node.
     *
     * @return DateTime
     */
    public function getShippingWindowFromDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setShippingWindowFromDate(DateTime $shippingWindowFromDate);

    /**
     * The latest date when the order line item is expected to leave the fulfillment node.
     *
     * @return DateTime
     */
    public function getShippingWindowToDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setShippingWindowToDate(DateTime $shippingWindowToDate);

    /**
     * The date-time when this delivery estimate was created
     *
     * @return DateTime
     */
    public function getDeliveryEstimateCreationTime();

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryEstimateCreationTime(DateTime $deliveryEstimateCreationTime);

    /**
     * Indicates if the delivery estimate should be displayed.
     *
     * @return DateTime
     */
    public function getDeliveryEstimateDisplayFlag();

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryEstimateDisplayFlag(DateTime $deliveryEstimateDisplayFlag);

    /**
     * not currently used.
     *
     * restrictions: optional
     * @return string
     */
    public function getDeliveryEstimateMessage();

    /**
     * @param string
     * @return self
     */
    public function setDeliveryEstimateMessage($deliveryEstimateMessage);
}
