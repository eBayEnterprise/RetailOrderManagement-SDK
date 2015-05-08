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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\IIterablePayload;

interface IItemIterable extends IIterablePayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const INSTOREPICKUP_ITEM_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\IInStorePickUpItem';
    const SHIPPING_ITEM_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\IShippingItem';

    /**
     * Get a new, empty item for shipping.
     * @return IShippingItemIterable
     */
    public function getEmptyShippingItem();

    /**
     * Get a new, empty item for in store pick up.
     * @return IInStorePickUpItemIterable
     */
    public function getEmptyInStorePickUpItem();
}
