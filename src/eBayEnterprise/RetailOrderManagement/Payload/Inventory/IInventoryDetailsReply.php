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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

/**
 * Payload used to fetch a fulfillment delivery estimate and ship from address
 * for one or more line items based on the ship-to address and shipping
 * method on each line item.
 */
interface IInventoryDetailsReply extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/checkout/1.0/Inventory-Service-InventoryDetails-1.0.xsd';
    const ROOT_NODE = 'InventoryDetailsResponseMessage';
    const UNAVAILABLE_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\IUnavailableItemIterable';
    const DETAIL_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\IDetailItemIterable';

    /**
     * Collection of items which cannot be fulfilled.
     *
     * @return IItemIterable
     */
    public function getUnavailableItems();

    /**
     * @param IItemIterable
     * @return self
     */
    public function setUnavailableItems(IItemIterable $items);

    /**
     * Collection of items which can be fulfiled.
     *
     * @return IItemIterable
     */
    public function getDetailItems();

    /**
     * @param IItemIterable
     * @return self
     */
    public function setDetailItems(IItemIterable $items);
}
