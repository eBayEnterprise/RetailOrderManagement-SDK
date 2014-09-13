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
use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout;
use eBayEnterprise\RetailOrderManagement\Payload\ISerializable;

interface ICreditCardAuthRequest extends ISerializable
{
    /**
     * The PaymentContext combines with the tender type in the URI to uniquely identify
     * a Payment Transaction for an order.
     *
     * @return IPaymentContext
     */
    function getPaymentContext();
    /**
     * Expiration date of the credit card.
     *
     * @return DateTime
     */
    function getExpirationDate();
    /**
     * The CVV2 code found on the back of credit cards.
     *
     * @return ICardSecurityCode
     */
    function getCardSecurityCode();
    /**
     * Amount to authorize
     *
     * @return IAmount
     */
    function getAmount();
    /**
     * First name of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingFirstName();
    /**
     * Last name of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingLastName();
    /**
     * Billing phone number of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingPhoneNo();
    /**
     * Billing Address of the credit card.
     *
     * @return Checkout\IPhysicalAddress
     */
    function getBillingAddress();
    /**
     * E-mail address of the customer making the purchase.
     *
     * @return Checkout\IEmailAddress
     */
    function getCustomerEmail();
    /**
     * IP Address of the customer making the purchase.
     *
     * @return Checkout\IIPv4Address
     */
    function getCustomerIPAddress();
    /**
     * First name of the person on the Shipping Address of the Order.
     *
     * @return string
     */
    function getShipToFirstName();
    /**
     * Last name of the person on the Shipping Address of the Order.
     *
     * @return string
     */
    function getShipToLastName();
    /**
     * Billing phone number of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getShipToPhoneNo();
    /**
     * @return Checkout\IPhysicalAddress
     */
    function getShippingAddress();
    /**
     * Indicates that this is an authorization re-submission to correct AVS or CVV2 error.
     * If set to true, this will process the transaction specifically as an AVS/CVV check.
     * This is important to set correctly, otherwise the cardholder will have their credit card authed multiple times
     * for the full payment amount when correcting AVS/CSC errors.
     *
     * @return bool
     */
    function isRequestToCorrectCVVOrAVSError();
    /**
     * 3D-Secure(Master Card)/Verified-by-Visa Data
     *
     * @return ISecureVerificationData
     */
    function getSecureVerificationData();
}
