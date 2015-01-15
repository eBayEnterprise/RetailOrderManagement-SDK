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

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPersonName;

interface IOrderCustomer extends IPersonName, ILoyaltyProgramContainer
{
    /**
     * Customer id assigned by the system taking the order.
     *
     * restrictions: optional, string length <= 40
     */
    public function getCustomerId();

    /**
     * @param string
     * @return self
     */
    public function setCustomerId($customerId);

    /**
     * Customer's gender.
     *
     * restrictions: optional, string length = 1
     * @return string
     */
    public function getGender();

    /**
     * @param string
     * @return self
     */
    public function setGender($gender);

    /**
     * Customer's date of birth.
     *
     * restrictions: optional, xsd:date
     * @return DateTime
     */
    public function getDateOfBirth();

    /**
     * @param DateTime
     * @return self
     */
    public function setDateOfBirth(DateTime $dateOfBirth);

    /**
     * Customer's email address.
     *
     * restrictions: optional, string with length <= 150
     * @return string
     */
    public function getEmailAddress();

    /**
     * @param string
     * @return self
     */
    public function setEmailAddress($emailAddress);

    /**
     * Customer's tax id.
     *
     * restrictions: optional, string with length <= 40
     * @return string
     */
    public function getTaxId();

    /**
     * @param string
     * @return self
     */
    public function setTaxId($taxId);

    /**
     * Is the customer tax exempt.
     *
     * restrictions: optional
     * @return bool
     */
    public function getIsTaxExempt();

    /**
     * @param bool
     * @return self
     */
    public function setIsTaxExempt($taxExempt);
}
