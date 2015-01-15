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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ICustomization extends IPayload, ICustomizationInstructionContainer
{
    const PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Get a new, empty price group for customization extended prices.
     *
     * @return IPriceGroup
     */
    public function getEmptyExtendedPrice();

    /**
     * Identifier used to group customizations instructions into logical sets.
     *
     * restrictions: optional
     * @return int
     */
    public function getCustomizationId();

    /**
     * @param int
     * @return self
     */
    public function setCustomizationId($customizationId);

    /**
     * Additional charges associated with the customization.
     *
     * restrictions: optional
     * @return IPriceGroup
     */
    public function getExtendedPrice();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setExtendedPrice(IPriceGroup $extendedPrice);

    /**
     * Item id used to specify the customization. May be for a physical
     * item or an accounting placeholder value.
     *
     * restrictions: string with length >= 1 and <= 20
     * @return string
     */
    public function getItemId();

    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId);

    /**
     * Get the item being customized.
     *
     * @return IOrderItem
     */
    public function getCustomizedItem();

    /**
     * @param IOrderItem
     * @return self
     */
    public function setCustomizedItem(IOrderItem $customizedItem);

    /**
     * Get the id of the item being customized. If an order item has been
     * set for the customized item, will return that item's id. Otherwise,
     * may return the id of an item that cannot yet be dereferenced but has
     * been supplied by other means, e.g. payload deserialization.
     *
     * @return string
     */
    public function getCustomizedItemId();
}
