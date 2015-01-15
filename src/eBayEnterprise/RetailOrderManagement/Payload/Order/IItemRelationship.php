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

interface IItemRelationship extends IPayload, IOrderItemReferenceContainer
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const TYPE_BUNDLE = 'BUNDLE';
    const TYPE_WARRANTY = 'WARRANTY';
    const TYPE_KIT = 'KIT';
    const TYPE_DYNAMIC = 'DYNAMIC';
    const TYPE_CUSTOMIZATION = 'CUSTOMIZATION';

    /**
     * The id of the order associated with the relationship.
     *
     * @return IOrderItem
     */
    public function getParentItem();

    /**
     * @param IOrderItem
     * @return self
     */
    public function setParentItem(IOrderItem $parentItem);

    /**
     * Type of relationship for the items. Allowed values are:
     * - BUNDLE: multiple items sold together
     * - WARRANTY: A warranty sold with a physic item
     * - KIT: Several items sold together to create a single unit
     * - DYNAMIC KIT: Several items, selected by the customer, sold together to create a single unit
     * - CUSTOMIZATION: Groups an item with a personalization or customization
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
     * Descriptive name for the relationship.
     *
     * restrictions: optional
     * @return string
     */
    public function getName();

    /**
     * @param string
     * @return self
     */
    public function setName($name);
}
