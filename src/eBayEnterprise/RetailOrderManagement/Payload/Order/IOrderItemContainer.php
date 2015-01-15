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

interface IOrderItemContainer
{
    const ORDER_ITEM_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemIterable';

    /**
     * Get all order items associated with the order.
     *
     * @return IOrderItemIterable
     */
    public function getOrderItems();

    /**
     * @param IOrderItemIterable
     * @return self
     */
    public function setOrderItems(IOrderItemIterable $items);
}
