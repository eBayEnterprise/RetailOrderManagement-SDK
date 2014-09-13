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
 * Interface IJurisdiction
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface IJurisdiction extends IXsdPrimitive
{
    /**
     * Identifies the jurisdiction's common classification based on its geopolitical and/or taxing context.
     *
     * @return string
     */
    function getJurisdictionLevel();
    /**
     * Jurisdiction code assigned by the relevant governmental authority.
     *
     * @return string
     */
    function getJurisdictionId();
}
