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

class CreditCardAuthRequest implements ICreditCardAuthRequest
{
    protected $requestId;
    protected $paymentContext;
    protected $expirationDate;
    protected $cardSecurityCode;
    protected $amount;
    protected $billingFirstName;
    protected $billingLastName;
    protected $billingPhoneNo;
    protected $billingAddress;
    protected $customerEmail;
    protected $customerIpAddress;
    protected $shipToFirstName;
    protected $shipToLastName;
    protected $shipToPhoneNo;
    protected $shippingAddress;
    protected $isRequestToCorrectCVVOrAVSError;
    protected $secureVerificationData;

    /**
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * @return Checkout\IRequestId
     */
    function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param Checkout\IRequestId $requestId
     * @return self
     */
    function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * The PaymentContext combines with the tender type in the URI to uniquely identify
     * a Payment Transaction for an order.
     *
     * @return IPaymentContext
     */
    function getPaymentContext()
    {
        return $this->paymentContext;
    }

    /**
     * @param IPaymentContext $context
     * @return self
     */
    function setPaymentContext(IPaymentContext $context)
    {
        $this->paymentContext = $context;
        return $this;
    }

    /**
     * Expiration date of the credit card.
     *
     * @return \DateTime
     */
    function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $date
     * @return self
     */
    function setExpirationDate(\DateTime $date)
    {
        $this->expirationDate = $date;
        return $this;
    }

    /**
     * The CVV2 code found on the back of credit cards.
     *
     * @return ICardSecurityCode
     */
    function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    /**
     * @param ICardSecurityCode $code
     * @return self
     */
    function setCardSecurityCode(ICardSecurityCode $code)
    {
        $this->cardSecurityCode = $code;
        return $this;
    }

    /**
     * Amount to authorize
     *
     * @return IAmount
     */
    function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param IAmount $amount
     * @return self
     */
    function setAmount(IAmount $amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * First name of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    /**
     * @param string $name
     * @return self
     */
    function setBillingFirstName($name)
    {
        $this->billingFirstName = $name;
        return $this;
    }

    /**
     * Last name of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingLastName()
    {
        return $this->billingLastName;
    }

    /**
     * @param string $name
     * @return self
     */
    function setBillingLastName($name)
    {
        $this->billingLastName = $name;
        return $this;
    }

    /**
     * Billing phone number of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getBillingPhoneNo()
    {
        return $this->billingPhoneNo;
    }

    /**
     * @param string $phone
     * @return self
     */
    function setBillingPhoneNo($phone)
    {
        $this->billingPhoneNo = $phone;
        return $this;
    }

    /**
     * Billing Address of the credit card.
     *
     * @return Checkout\IPhysicalAddress
     */
    function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Checkout\IPhysicalAddress $address
     * @return self
     */
    function setBillingAddress(Checkout\IPhysicalAddress $address)
    {
        $this->billingAddress = $address;
        return $this;
    }

    /**
     * E-mail address of the customer making the purchase.
     *
     * @return Checkout\IEmailAddress
     */
    function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    /**
     * @param Checkout\IEmailAddress $email
     * @return self
     */
    function setCustomerEmail(Checkout\IEmailAddress $email)
    {
        $this->customerEmail = $email;
        return $this;
    }

    /**
     * IP Address of the customer making the purchase.
     *
     * @return Checkout\IIPv4Address
     */
    function getCustomerIPAddress()
    {
        return $this->customerIpAddress;
    }

    /**
     * @param Checkout\IIPv4Address $ip
     * @return self
     */
    function setCustomerIPAddress(Checkout\IIPv4Address $ip)
    {
        $this->customerIpAddress = $ip;
        return $this;
    }

    /**
     * First name of the person on the Shipping Address of the Order.
     *
     * @return string
     */
    function getShipToFirstName()
    {
        return $this->shipToFirstName;
    }

    /**
     * @param string $name
     * @return self
     */
    function setShipToFirstName($name)
    {
        $this->shipToFirstName = $name;
        return $this;
    }

    /**
     * Last name of the person on the Shipping Address of the Order.
     *
     * @return string
     */
    function getShipToLastName()
    {
        return $this->shipToLastName;
    }

    /**
     * @param string $name
     * @return self
     */
    function setShipToLastName($name)
    {
        $this->shipToLastName = $name;
        return $this;
    }

    /**
     * Billing phone number of the person on the Billing Address of the credit card
     *
     * @return string
     */
    function getShipToPhoneNo()
    {
        return $this->shipToPhoneNo;
    }

    /**
     * @param string $phone
     * @return self
     */
    function setShipToPhoneNo($phone)
    {
        $this->shipToPhoneNo = $phone;
        return $this;
    }

    /**
     * @return Checkout\IPhysicalAddress
     */
    function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    /**
     * @param Checkout\IPhysicalAddress $address
     * @return self
     */
    function setShippingAddress(Checkout\IPhysicalAddress $address)
    {
        $this->shippingAddress = $address;
        return $this;
    }

    /**
     * Indicates that this is an authorization re-submission to correct AVS or CVV2 error.
     * If set to true, this will process the transaction specifically as an AVS/CVV check.
     * This is important to set correctly, otherwise the cardholder will have their credit card authed multiple times
     * for the full payment amount when correcting AVS/CSC errors.
     *
     * @return bool
     */
    function getIsRequestToCorrectCVVOrAVSError()
    {
        return $this->isRequestToCorrectCVVOrAVSError;
    }

    /**
     * @param bool $flag
     * @return self
     */
    function setIsRequestToCorrectCVVOrAVSError($flag)
    {
        $this->isRequestToCorrectCVVOrAVSError = (bool) $flag;
        return $this;
    }

    /**
     * 3D-Secure(Master Card)/Verified-by-Visa Data
     *
     * @return ISecureVerificationData
     */
    function getSecureVerificationData()
    {
        return $this->secureVerificationData;
    }

    /**
     * @param ISecureVerificationData $data
     * @return self
     */
    function setSecureVerificationData(ISecureVerificationData $data)
    {
        $this->secureVerificationData = $data;
        return $this;
    }
}