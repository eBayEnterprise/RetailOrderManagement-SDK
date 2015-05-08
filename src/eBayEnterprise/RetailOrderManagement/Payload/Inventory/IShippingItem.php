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
 * Order line item in a checkout inventory API inventory details request or allocation request.
 */
interface IShippingItem extends IOrderItem, IAddress
{
    /**
     * Shipping Carrier such as "UPS" or "FEDEX"
     *
     * restrictions: optional, length <= 40
     * @return string
     */
    public function getShippingMethod();

    /**
     * @param string
     * @return self
     */
    public function setShippingMethod($shippingMethod);

    /**
     * Indicates the carrier method for example Std_GnD or 2Day
     *
     * restrictions: optional, length <= 40
     * @return string
     */
    public function getShippingMethodMode();

    /**
     * @param string
     * @return self
     */
    public function setShippingMethodMode($shippingMethodMode);

    /**
     * Specifies the text to display when the mode is quierried.
     *
     * restrictions: optional
     * @return string
     */
    public function getShippingMethodDisplayText();

    /**
     * @param string
     * @return self
     */
    public function setShippingMethodDisplayText($shippingMethodDisplayText);
}
