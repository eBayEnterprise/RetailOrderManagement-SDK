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

interface IOrderRejected extends IPayload, IOrderEvent
{
    const ROOT_NODE = 'OrderRejected';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/events/1.0/events/Order-Rejected-Event-1.0.xsd';
    const REJECTED_XML_TEMPLATE =
        '<%1$s xmlns="%2$s" customerOrderId="%3$s" storeId="%4$s" orderCreateTimestamp="%5$s">%6$s</%1$s>';
    const REASON_XML_TEMPLATE = '<%1$s code="%2$s">%3$s</%1$s>';
    const REASON_XML_ROOT = 'Reason';
    const PROPERTY_ORDER_CREATE_TIMESTAMP = 'orderCreateTimestamp';

    /**
     * get the order create timestamp
     * @return \DateTime
     */
    public function getOrderCreateTimestamp();

    /**
     * set the order create timestamp
     * @param \DateTime $timestamp
     * @return self
     */
    public function setOrderCreateTimestamp(\DateTime $timestamp);

    /**
     * get the order rejected reason.
     * @return String
     */
    public function getReason();

    /**
     * set the order rejected reason.
     * @param String $reason
     * @return self
     */
    public function setReason($reason);

    /**
     * get the order rejected reason attribute code.
     * @return String
     */
    public function getCode();

    /**
     * set the order rejected reason attribute code.
     * @param String $code
     * @return self
     */
    public function setCode($code);
}
