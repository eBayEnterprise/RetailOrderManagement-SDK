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

/**
 * Interface for order event messages received over AMQP.
 */
interface IOrderEvent
{
    /**
     * Return the type of order event being processed. This will likely be
     * the serialized payload's root XML node name but may not always be.
     * @return string
     */
    public function getEventType();
    /**
     * Identifier of the store in which the order was placed.
     *
     * @return string
     */
    public function getStoreId();
    /**
     * @param string
     * @return self
     */
    public function setStoreId($storeId);
    /**
     * Unique test string identifying the order.
     *
     * xsd restriction: 1-24 characters
     * @return string
     */
    public function getCustomerOrderId();
    /**
     * @param string
     * @return self
     */
    public function setCustomerOrderId($orderId);
}
