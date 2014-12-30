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

interface IOrderCancel extends IPayload, IOrderEvent, ILoyaltyProgramCustomer, IOrderItemContainer, ICurrency
{
    const ROOT_NODE = 'OrderCancelled';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/events/1.0/events/Order-Cancel-Event-1.0.xsd';

    /**
     * Get a description of the reason for the order cancellation.
     *
     * @return string
     */
    public function getCancelReason();
    /**
     * @param string
     * @return self
     */
    public function setCancelReason($cancelReason);
    /**
     * Code for the cancellation reason.
     *
     * @return string
     */
    public function getCancelReasonCode();
    /**
     * @param string
     * @return self
     */
    public function setCancelReasonCode($cancelReasonCode);
}
