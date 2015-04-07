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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

interface IPayPalGetExpressCheckoutReply extends IPayPalGetExpressCheckout, IBillingAddress, IShippingAddress
{
    const ROOT_NODE = 'PayPalGetExpressCheckoutReply';

    /**
     * Response code like Success, Failure etc
     *
     * @return string
     */
    public function getResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setResponseCode($code);

    /**
     * Email address of the payer. Character length and limitations: 127 single-byte characters
     *
     * @return string
     */
    public function getPayerEmail();

    /**
     * @param string
     * @return self
     */
    public function setPayerEmail($email);

    /**
     * Unique identifier of the customer's PayPal account. Character length and limitations: 17 single-byte characters
     *
     * @return string
     */
    public function getPayerId();

    /**
     * @param string
     * @return self
     */
    public function setPayerId($id);

    /**
     * Status of payer's email address.
     * "verified" or "unverified"
     *
     * @return string
     */
    public function getPayerStatus();

    /**
     * @param string
     * @return self
     */
    public function setPayerStatus($payerStatus);

    /**
     * A title you can assign to the payer. Typically "Dr.", "Mr.", "Ms." etc.
     *
     * @return string
     */
    public function getPayerNameHonorific();

    /**
     * @param string
     * @return self
     */
    public function setPayerNameHonorific($hon);

    /**
     * The surname of the payer.
     *
     * @return string
     */
    public function getPayerLastName();

    /**
     * @param string
     * @return self
     */
    public function setPayerLastName($name);

    /**
     * The payer's middle name.
     *
     * @return string
     */
    public function getPayerMiddleName();

    /**
     * @param string
     * @return self
     */
    public function setPayerMiddleName($name);

    /**
     * The payer's first name.
     *
     * @return string
     */
    public function getPayerFirstName();

    /**
     * @param string
     * @return self
     */
    public function setPayerFirstName($name);

    /**
     * Payment sender's country of residence using standard two-character ISO 3166 country codes.
     * Character length and limitations: Two single-byte characters.
     *
     * @link http://countrycode.org/
     * @return string
     */
    public function getPayerCountry();

    /**
     * @param string
     * @return self
     */
    public function setPayerCountry($country);

    /**
     * Payer's phone on file with PayPal.
     *
     * @return string
     */
    public function getPayerPhone();

    /**
     * @param string
     * @return self
     */
    public function setPayerPhone($phone);

    /**
     * Billing address status to be sent to the Order Management System
     *
     * @return string
     */
    public function getBillingAddressStatus();

    /**
     * @param string
     * @return self
     */
    public function setBillingAddressStatus($status);

    /**
     * Shipping address status to be sent to the Order Management System
     *
     * @return string
     */
    public function getShippingAddressStatus();

    /**
     * @param string
     * @return self
     */
    public function setShippingAddressStatus($status);

    /**
     * Should downstream systems consider this reply a success?
     *
     * @return bool
     */
    public function isSuccess();
}
