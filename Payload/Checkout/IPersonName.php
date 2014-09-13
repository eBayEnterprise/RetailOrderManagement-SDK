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
 * Interface IPersonName
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface IPersonName extends ISerializable
{
    /**
     * A title you can assign to a person. Typically "Dr.", "Mr.", "Ms." etc.
     *
     * @return string
     */
    function getHonorific();
    /**
     * The surname of the person.
     *
     * @return string
     */
    function getLastName();
    /**
     * The middle name or names of the person.
     *
     * @return string
     */
    function getMiddleName();
    /**
     * The first name of the person.
     *
     * @return string
     */
    function getFirstName();
}
