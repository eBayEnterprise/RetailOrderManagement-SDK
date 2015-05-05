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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IOrderCancelRequest extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'OrderCancelRequest';
    const XSD = '/checkout/1.0/Order-Service-Cancel-1.0.xsd';

    /**
     * The type of order as defined in Sterling.
     * Allowable Values: SALES, RETURN, PURCHASE, TRANSFER
     *
     * @return string
     */
    public function getOrderType();

    /**
     * @param  string
     * @return self
     */
    public function setOrderType($orderType);

    /**
     * The unique text string that identifies an order.
     * Allowable Values: Text string
     * Required: Yes
     * Length: 24
     * Default Value: blank
     * Restrictions: This string must be unique within the webstore.
     *
     * @return string
     */
    public function getCustomerOrderId();

    /**
     * @param string
     * @return self
     */
    public function setCustomerOrderId($customerOrderId);

    /**
     * A client generated code for the cancellation.
     *
     * @return string
     */
    public function getReasonCode();

    /**
     * @param  string
     * @return self
     */
    public function setReasonCode($reasonCode);

    /**
     * A description for the client cancellation code.
     *
     * length <= 254
     * restrictions: optional
     * @return string
     */
    public function getReason();

    /**
     * @param string
     * @return self
     */
    public function setReason($reason);
}
