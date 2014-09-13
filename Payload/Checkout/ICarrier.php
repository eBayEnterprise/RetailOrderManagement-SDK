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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

/**
 * Shipping Carrier such as "UPS" or "FEDEX"
 * Carrier mode is such as 1Day or Ground, etc.
 * Represents the logical Carriers (SCAC) and modes (CarrierServiceCode)
 *
 * Interface ICarrier
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface ICarrier extends IString40
{
    /**
     * @return IString40
     */
    function getMode();
    /**
     * @return string
     */
    function getDisplayText();
}
