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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IOrderDetailResponse extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'OrderDetailResponse';
    const XSD = '/checkout/1.0/Order-Service-Detail-1.0.xsd';
    const ORDER_RESPONSE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IOrderResponse';

    /**
     * The type of order as defined in Sterling.
     * Allowable Values: SALES, RETURN, PURCHASE, TRANSFER
     *
     * @return string
     */
    public function getOrderType();

    /**
     * @param string
     * @return self
     */
    public function setOrderType($orderType);

    /**
     * Denotes the order as a test order. Values for this attribute are set up within the OMS.
     * Allowable Values:
     *     - TEST_WEBONLY - Webstore does not send the order to the Order service. This tests checkout flow on the webstore, but the order is not sent to OMS. Useful for demo purposes.
     *     - TEST_AUTOCANCEL - Order is sent to OMS, which automatically cancels it. Tests that the order gets to the OMS.
     *     - TEST_NORELEASE - OMS processes but does not ship the order. Tests payment activation, including taxes
     *     - TEST_AUTOSHIP
     *
     * @return string
     */
    public function getTestType();

    /**
     * @param string
     * @return self
     */
    public function setTestType($testType);

    /**
     * Will only be populated for Order Search requests. Will not be returned on Related Order Search requests.
     *
     * @return bool
     */
    public function getCancellable();

    /**
     * @param bool
     * @return self
     */
    public function setCancellable($cancellable);

    /**
     * This is the top node for representing an order detail response
     *
     * @return IOrderResponse
     */
    public function getOrder();

    /**
     * @param  IOrderResponse
     * @return self
     */
    public function setOrder(IOrderResponse $order);
}
