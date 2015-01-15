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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;

interface IEstimatedDeliveryDate
{
    const MODE_ENABLED = 'ENABLED';
    const MODE_CALIBRATION = 'CALIBRATION';
    const MODE_LEGACY = 'LEGACY';
    const MESSAGE_TYPE_DELIVERYDATE = 'DeliveryDate';
    const MESSAGE_TYPE_SHIPDATE = 'ShipDate';
    const MESSAGE_TYPE_NONE = 'None';

    /**
     * Beginning date of the estimated delivery window.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getEstimatedDeliveryWindowFrom();

    /**
     * @param DateTime
     * @return self
     */
    public function setEstimatedDeliveryWindowFrom(DateTime $estimatedDeliveryWindowFrom);

    /**
     * End date of the estimated deliver window.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getEstimatedDeliveryWindowTo();

    /**
     * @param DateTime
     * @return self
     */
    public function setEstimatedDeliveryWindowTo(DateTime $estimatedDeliveryWindowTo);

    /**
     * Start date of the shipping window.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getEstimatedShippingWindowFrom();

    /**
     * @param DateTime
     * @return self
     */
    public function setEstimatedShippingWindowFrom(DateTime $estimatedShippingWindowFrom);

    /**
     * End date of the shipping window.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getEstimatedShippingWindowTo();

    /**
     * @param DateTime
     * @return self
     */
    public function setEstimatedShippingWindowTo(DateTime $estimatedShippingWindowTo);

    /**
     * Sets the type of messaging to display on the order's delivery status.
     * May be one of:
     * - ENABLED: The Webstore provides EDD from inventory services and displays
     *   values to customer per line item.
     * - CALIBRATION: The Webstore provides EDD from inventory services but
     *   displays a more generic message, e.g. "Leaves Warehouse in 1-2 Days."
     * - LEGACY: The webstore ignores EDD from inventory services and displays
     *   a generic message, e.g. "Leaves Warehouse in 1-2 Days."
     *
     * restrictions: optional, one of "ENABLED", "CALIBRATION", "LEGACY"
     * @return string
     */
    public function getEstimatedDeliveryMode();

    /**
     * @param string
     * @return self
     */
    public function setEstimatedDeliveryMode($estimatedDeliveryMode);

    /**
     * The type of message to display to the user. May be:
     * - DeliveryDate
     * - ShipDate
     * - None
     *
     * restrictions: one of "DeliveryDate", "ShipDate", "None"
     * @return string
     */
    public function getEstimatedDeliveryMessageType();

    /**
     * @param string
     * @return self
     */
    public function setEstimatedDeliveryMessageType($estimatedDeliveryMessageType);

    /**
     * Template to use to display the estimated delivery date.
     *
     * restrictions: optional
     * @return string
     */
    public function getEstimatedDeliveryTemplate();

    /**
     * @param string
     * @return self
     */
    public function setEstimatedDeliveryTemplate($estimatedDeliveryTemplate);
}
