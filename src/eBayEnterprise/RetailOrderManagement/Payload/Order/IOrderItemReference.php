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

interface IOrderItemReference extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Get the order item referenced.
     *
     * @return IOrderItem
     */
    public function getReferencedItem();

    /**
     * @param IOrderItem
     * @return self
     */
    public function setReferencedItem(IOrderItem $itemId);

    /**
     * Get the id of the order item referenced by this payload. When a referenced
     * order item has been set on this payload, will return the id of that
     * payload. Otherwise, may be an id that will eventually be resolvable to
     * an order item payload.
     *
     * @return string
     */
    public function getReferencedItemId();
}
