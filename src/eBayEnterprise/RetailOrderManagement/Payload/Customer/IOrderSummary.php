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

namespace eBayEnterprise\RetailOrderManagement\Payload\Customer;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use DateTime;

interface IOrderSummary extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'OrderSummary';

    /**
     * Unique key used for relating orders through IDREFs
     *
     * @return string
     */
    public function getId();

    /**
     * @param  string
     * @return self
     */
    public function setId($id);

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
     * This indicates what, if any, specific purpose this order was created for.
     * The only current valid value is 'EXCHANGE' which means the order is
     * created for exchange purpose.
     *
     * @return string
     */
    public function getOrderPurpose();

    /**
     * @param string
     * @return self
     */
    public function setOrderPurpose($orderPurpose);

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
     * The date and time the order was last modified in OMS.
     *
     * @return DateTime
     */
    public function getModifiedTime();

    /**
     * @param DateTime
     * @return self
     */
    public function setModifiedTime(DateTime $modifiedTime);

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
     * The customer unique id relating to the order.
     *
     * @return string
     */
    public function getCustomerId();

    /**
     * @param  string
     * @return self
     */
    public function setCustomerId($customerId);

   /**
     * The unique text string that identifies an order.
     * Allowable Values: Text string
     * Required: Yes
     * Length: 24
     * Restrictions: This string must be unique within the webstore
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
     * The date and time the order was created in OMS.
     *
     * @return DateTime
     */
    public function getOrderDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setOrderDate(DateTime $orderDate);

    /**
     * If a customer service representative put this order through
     * for a customer, there Id is returned here.
     *
     * @return string
     */
    public function getDashboardRepId();

    /**
     * @param string
     * @return self
     */
    public function setDashboardRepId($dashboardRepId);

    /**
     * The order current status in OMS.
     *
     * @return string
     */
    public function getStatus();

    /**
     * @param string
     * @return self
     */
    public function setStatus($status);

    /**
     * The order total in OMS.
     *
     * @return string
     */
    public function getOrderTotal();

    /**
     * @param string
     * @return self
     */
    public function setOrderTotal($orderTotal);

    /**
     * @return string
     */
    public function getSource();

    /**
     * @param string
     * @return self
     */
    public function setSource($source);

    /**
     * A chained order refers to a Drop Shipped order
     *
     * @return string
     */
    public function getChainedOrder();

    /**
     * @param string
     * @return self
     */
    public function setChainedOrder($chainedOrder);

    /**
     * Only current valid value is "DROP_SHIP"
     *
     * @return string
     */
    public function getType();

    /**
     * @param string
     * @return self
     */
    public function setType($type);

    /**
     * Refers to a parent order
     *
     * @return string
     */
    public function getParentRef();

    /**
     * @param string
     * @return self
     */
    public function setParentRef($parentRef);

    /**
     * A derived order is a returned and/or exchanged order
     *
     * @return string
     */
    public function getDerivedOrder();

    /**
     * @param string
     * @return self
     */
    public function setDerivedOrder($derivedOrder);
}
