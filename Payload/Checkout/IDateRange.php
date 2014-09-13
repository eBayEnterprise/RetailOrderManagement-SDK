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
use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\ISerializable;

/**
 * A period of time. The "From" date must precede the "To" date. You must specify both values.
 * Used, for example, to represent an estimated delivery date range.
 *
 * Interface IDateRange
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface IDateRange extends ISerializable
{
    /**
     * @return DateTime
     */
    function getFrom();

    /**
     * @return DateTime
     */
    function getTo();
}
