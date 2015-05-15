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

interface IRequestQuantityItem extends IQuantityItem
{
    const ROOT_NODE = 'QuantityRequest';

    /**
     * Type of the requested fulfillment location.
     *
     * restrictions: optional, only supports type of "ISPU"
     * @param string
     */
    public function getFulfillmentLocationType();

    /**
     * @param string
     * @return self
     */
    public function setFulfillmentLocationType($fulfillmentLocationType);

    /**
     * Identified for the requested fulfillment location.
     *
     * restrictions: optional, 1 <= length <= 100
     * @return string
     */
    public function getFulfillmentLocationId();

    /**
     * @param string
     * @return self
     */
    public function setFulfillmentLocationId($fulfillmentLocationId);
}
