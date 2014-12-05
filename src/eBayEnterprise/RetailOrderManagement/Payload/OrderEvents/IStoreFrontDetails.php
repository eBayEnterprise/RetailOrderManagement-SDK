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

interface IStoreFrontDetails extends IDestination, IPhysicalAddress
{
    /**
     * The unique string that identifies a specific store.
     *
     * xsd restrictions: max length 40 characters
     * @return string
     */
    public function getStoreCode();
    /**
     * @param string
     * @return self
     */
    public function setStoreCode($storeCode);
    /**
     * The way the store is referrred to, for example "Springfield" or "Eastview Mall".
     *
     * xsd restrictions: max length 100 characters
     * @return string
     */
    public function getStoreName();
    /**
     * @param string
     * @return self
     */
    public function setStoreName($storeName);
    /**
     * Email address associated with the store location.
     *
     * xsd restrictions: 1-70 characters
     * @return string
     */
    public function getEmailAddress();
    /**
     * @param string
     * @return self
     */
    public function setEmailAddress($emailAddress);
    /**
     * @return string
     */
    public function getDirections();
    /**
     * @param string
     * @return self
     */
    public function setDirections($directions);
    /**
     * Store hours of operation.
     *
     * @return string
     */
    public function getHours();
    /**
     * @param string
     * @return self
     */
    public function setHours($hours);
    /**
     * Store location phone number.
     *
     * @return string
     */
    public function getPhoneNumber();
    /**
     * @param string
     * @return self
     */
    public function setPhoneNumber($phoneNumber);
}
