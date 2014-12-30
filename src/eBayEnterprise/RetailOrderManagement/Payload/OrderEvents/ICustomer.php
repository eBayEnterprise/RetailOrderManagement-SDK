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

interface ICustomer
{
    /**
     * Assigned by the system that is taking the order. This can be either an
     * id created by the customer, for example, when the customer created an
     * account, or, if the customer did not want to create an account, the
     * system creates an id, which is then used for guest checkouts. Therefore
     * this field always has a value, even if the customer does not actively
     * create an account.
     *
     * xsd restriction: 1-40 characters
     * @return string
     */
    public function getCustomerId();
    /**
     * @param string
     * @return self
     */
    public function setCustomerId($customerId);
    /**
     * Customer first name as appears in billing information.
     *
     * xsd restrictions: max length 64 characters
     * @return string
     */
    public function getCustomerFirstName();
    /**
     * @param string
     * @return self
     */
    public function setCustomerFirstName($customerFirstName);
    /**
     * Customer last name as it appears in billing information.
     *
     * xsd restrictions: max length 64 characters
     * @return string
     */
    public function getCustomerLastName();
    /**
     * @param string
     * @return self
     */
    public function setCustomerLastName($customerLastName);
    /**
     * Customer middle name as it appears in billing information.
     *
     * xsd restrictions: max length 40 characters
     * @return string
     */
    public function getCustomerMiddleName();
    /**
     * @param string
     * @return self
     */
    public function setCustomerMiddleName($customerMiddleName);
    /**
     * Customer honorific title as it appears in billing information.
     *
     * xsd restrictions: max length 10 characters
     * @return string
     */
    public function getCustomerHonorificName();
    /**
     * @param string
     * @return self
     */
    public function setCustomerHonorificName($customerHonorificName);
    /**
     * Customer email address as it appears in billing information.
     *
     * xsd restrictions: max length 150 characters
     * @return string
     */
    public function getCustomerEmailAddress();
    /**
     * @param string
     * @return self
     */
    public function setCustomerEmailAddress($customerEmailAddress);
}
