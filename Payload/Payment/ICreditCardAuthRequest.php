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

use eBayEnterprise\RetailOrderManagement\Payload\Checkout;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ICreditCardAuthRequest extends IPayload
{
    /**
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * @return Checkout\IRequestId
     */
    function getRequestId();
    /**
     * @param Checkout\IRequestId $requestId
     * @return self
     */
    function setRequestId($requestId);
    /**
     * The PaymentContext combines with the tender type in the URI to uniquely identify
     * a Payment Transaction for an order.
     *
     * @return IPaymentContext
     */
    function getPaymentContext();
    /**
     * @param IPaymentContext $context
     * @return self
     */
    function setPaymentContext(IPaymentContext $context);
    /**
     * Expiration date of the credit card.
     *
     * @return \DateTime
     */
    function getExpirationDate();
    /**
     * @param \DateTime $date
     * @return self
     */
    function setExpirationDate(\DateTime $date);
    /**
     * The CVV2 code found on the back of credit cards.
     *
     * @return ICardSecurityCode
     */
    function getCardSecurityCode();
    /**
     * @param ICardSecurityCode $code
     * @return self
     */
    function setCardSecurityCode(ICardSecurityCode $code);
    /**
     * Amount to authorize
     *
     * @return IAmount
     */
    function getAmount();
    /**
     * @param IAmount $amount
     * @return self
     */
    function setAmount(IAmount $amount);
    /**
     * First name of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingFirstName();
    /**
     * @param string $name
     * @return self
     */
    function setBillingFirstName($name);
    /**
     * Last name of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingLastName();
    /**
     * @param string $name
     * @return self
     */
    function setBillingLastName($name);
    /**
     * Billing phone number of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingPhoneNo();
    /**
     * @param string $phone
     * @return self
     */
    function setBillingPhoneNo($phone);
    /**
     * Billing Address of the credit card.
     *
     * @return Checkout\IPhysicalAddress
     */
    function getBillingAddress();
    /**
     * @param Checkout\IPhysicalAddress $address
     * @return self
     */
    function setBillingAddress(Checkout\IPhysicalAddress $address);
    /**
     * E-mail address of the customer making the purchase.
     *
     * @return Checkout\IEmailAddress
     */
    function getCustomerEmail();
    /**
     * @param Checkout\IEmailAddress $email
     * @return self
     */
    function setCustomerEmail(Checkout\IEmailAddress $email);
    /**
     * IP Address of the customer making the purchase.
     *
     * @return Checkout\IIPv4Address
     */
    function getCustomerIPAddress();
    /**
     * @param Checkout\IIPv4Address $ip
     * @return self
     */
    function setCustomerIPAddress(Checkout\IIPv4Address $ip);
    /**
     * First name of the person on the Shipping Address of the Order.
     *
     * @return string
     */
    function getShipToFirstName();
    /**
     * @param string $name
     * @return self
     */
    function setShipToFirstName($name);
    /**
     * Last name of the person on the Shipping Address of the Order.
     *
     * @return string
     */
    function getShipToLastName();
    /**
     * @param string $name
     * @return self
     */
    function setShipToLastName($name);
    /**
     * Billing phone number of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getShipToPhoneNo();
    /**
     * @param string $phone
     * @return self
     */
    function setShipToPhoneNo($phone);
    /**
     * @return Checkout\IPhysicalAddress
     */
    function getShippingAddress();
    /**
     * @param Checkout\IPhysicalAddress $address
     * @return self
     */
    function setShippingAddress(Checkout\IPhysicalAddress $address);
    /**
     * Indicates that this is an authorization re-submission to correct AVS or CVV2 error.
     * If set to true, this will process the transaction specifically as an AVS/CVV check.
     * This is important to set correctly, otherwise the cardholder will have their credit card authed multiple times
     * for the full payment amount when correcting AVS/CSC errors.
     *
     * @return bool
     */
    function getIsRequestToCorrectCVVOrAVSError();
    /**
     * @param bool $flag
     * @return self
     */
    function setIsRequestToCorrectCVVOrAVSError($flag);
    /**
     * 3D-Secure(Master Card)/Verified-by-Visa Data
     *
     * @return ISecureVerificationData
     */
    function getSecureVerificationData();
    /**
     * @param ISecureVerificationData $data
     * @return self
     */
    function setSecureVerificationData(ISecureVerificationData $data);
}
