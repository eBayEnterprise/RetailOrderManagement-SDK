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

interface IAdjustedOrderItem extends IPayload, IOrderItemDescription, IItemPriceAdjustmentContainer
{
    const ROOT_NODE = 'OrderItem';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const LINE_NUMBER_MIN = 0;
    const LINE_NUMBER_MAX = 999;
    const ITEM_ID_MAX_LENGTH = 20;

    /**
     * Line number of the item in the cart when the order was placed.
     *
     * xsd restriction: min value 1, max value 999
     * @return int
     */
    public function getLineNumber();
    /**
     * @param int
     * @return self
     */
    public function setLineNumber($lineNumber);
    /**
     * Identifier for the inventoriable product. SKU
     *
     * xsd restriction: 1-20 characters
     * @return string
     */
    public function getItemId();
    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId);
}
