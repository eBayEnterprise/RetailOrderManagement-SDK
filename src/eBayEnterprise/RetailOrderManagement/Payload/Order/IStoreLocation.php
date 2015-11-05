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

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPhysicalAddress;

interface IStoreLocation extends IDestination, IPhysicalAddress
{
    /**
     * Unique identifier for a specific store.
     *
     * restrictions: optional, string with length <= 40
     * @return string
     */
    public function getStoreCode();

    /**
     * @param string
     * @return self
     */

    public function setStoreCode($storeCode);
    /**
     * Name of the store.
     *
     * restrictions: optional, string with length <= 100
     * @return string
     */
    public function getStoreName();

    /**
     * @param string
     * @return self
     */

    public function setStoreName($storeName);

    /**
     * Email address that can be used to contact the store location.
     *
     * restrictions: optional, string with length <= 70, should be a valid email address
     * @return string
     */
    public function getEmailAddress();

    /**
     * @param string
     * @return self
     */
    public function setEmailAddress($emailAddress);

    /**
     * Phone number that can be used to contact the store location.
     *
     * restrictions: optional, string no length required should be a valid phone number
     * @return string
     */
    public function getPhone();

    /**
     * @param string
     * @return self
     */
    public function setPhone($phone);
}
