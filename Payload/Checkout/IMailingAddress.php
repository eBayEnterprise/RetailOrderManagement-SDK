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
use eBayEnterprise\RetailOrderManagement\Payload\ISerializable;

/**
 * Combines a name with a physical address.
 *
 * Interface IMailingAddress
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface IMailingAddress extends ISerializable
{
    /**
     * @return IPersonName
     */
    function getPersonName();
    /**
     * The number, street, city state, postal code and country that define a location.
     *
     * @return IPhysicalAddress
     */
    function getAddress();
}
