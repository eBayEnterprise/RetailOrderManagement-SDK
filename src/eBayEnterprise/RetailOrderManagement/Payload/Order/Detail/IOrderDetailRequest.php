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

interface IOrderDetailRequest extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'OrderDetailRequest';
    const XSD = '/checkout/1.0/Order-Service-Detail-1.0.xsd';

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
     * @param  string
     * @return self
     */
    public function setCustomerOrderId($customerOrderId);
}
