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

interface IEddMessage extends IPayload, IDeliveryWindow
{
    const ROOT_NODE = 'EDDMessage';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * This field enables or disables legacy Estimated Delivery Date (EDD) messaging on the order's delivery status.
     *
     * @return string
     *         Allowable Values:
     *              ENABLED: webstore uses the response from inventoryDetails request and displays EDD
     *                       to customer per line item in cart. edd is passed through order message to OMS.
     *              DISABLED: webstore ignores response from inventoryDetails request and displays
     *                       "legacy messaging" (e.g. "Leaves Warehouse in 1-2 days").
     *              CALIBRATION: webstore passes calculated EDD dates from inventoryDetails request through
     *                           order message, but displays legacy messaging in webstore.
     *         length: 11
     */
    public function getMode();

    /**
     * @param string
     * @return self
     */
    public function setMode($mode);

    /**
     * Message to the customer about the delivery. Usually provides details about the delivery.
     *
     * @return string
     *         - Possible values are DeliveryDate, ShipDate, or None.
     */
    public function getMessageType();

    /**
     * @param string
     * @return self
     */
    public function setMessageType($messageType);

    /**
     * The Template text value has a varying number string variable markers depending on the template selected by OMS.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * @param string
     * @return self
     */
    public function setTemplate($template);
}
