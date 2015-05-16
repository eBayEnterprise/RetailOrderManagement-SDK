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

interface IOrderSearch extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'OrderSearch';

    /**
     * Finds orders for a particular customer.
     * Assigned by the system that is taking the order. This can be either an ID
     * created by the customer, for example, when the customer created an account, or,
     * if the customer did not want to create an account, the system creates an ID,
     * which is then used for guest checkouts. Therefore this field always has a value,
     * even if the customer does not actively create an account.
     * Allowable Values: Text string
     * Required: Yes
     * Length: 40
     * Default Value: blank
     * Restrictions: Must be unique for each customer.
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
     * Finds orders related to this order id such as return orders.
     * The unique text string that identifies an order.
     * Allowable Values: Text string
     * Required: Yes
     * Length: 24
     * Default Value: blank
     * Restrictions: This string must be unique within the web-store.
     *
     * @return string
     */
    public function getCustomerOrderId();

    /**
     * @param string
     * @return self
     */
    public function setCustomerOrderId($customerOrderId);
}
