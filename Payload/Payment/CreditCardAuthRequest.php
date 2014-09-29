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

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function getPaymentContext()
    {
        return $this->paymentContext;
    }

    public function setPaymentContext($context)
    {
        $this->paymentContext = $context;
        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTime $date)
    {
        $this->expirationDate = $date;
        return $this;
    }

    public function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    public function setCardSecurityCode($code)
    {
        $this->cardSecurityCode = $code;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    public function setBillingFirstName($name)
    {
        $this->billingFirstName = $name;
        return $this;
    }

    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    public function setBillingLastName($name)
    {
        $this->billingLastName = $name;
        return $this;
    }

    public function getBillingPhoneNo()
    {
        return $this->billingPhoneNo;
    }

    public function setBillingPhoneNo($phone)
    {
        $this->billingPhoneNo = $phone;
        return $this;
    }

    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    public function setBillingAddress($address)
    {
        $this->billingAddress = $address;
        return $this;
    }

    public function getCustomerEmail()
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail($email)
    {
        $this->customerEmail = $email;
        return $this;
    }

    public function getCustomerIPAddress()
    {
        return $this->customerIpAddress;
    }

    public function setCustomerIPAddress($ip)
    {
        $this->customerIpAddress = $ip;
        return $this;
    }

    public function getShipToFirstName()
    {
        return $this->shipToFirstName;
    }

    public function setShipToFirstName($name)
    {
        $this->shipToFirstName = $name;
        return $this;
    }

    public function getShipToLastName()
    {
        return $this->shipToLastName;
    }

    public function setShipToLastName($name)
    {
        $this->shipToLastName = $name;
        return $this;
    }

    public function getShipToPhoneNo()
    {
        return $this->shipToPhoneNo;
    }

    public function setShipToPhoneNo($phone)
    {
        $this->shipToPhoneNo = $phone;
        return $this;
    }

    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress($address)
    {
        $this->shippingAddress = $address;
        return $this;
    }

    public function getIsRequestToCorrectCVVOrAVSError()
    {
        return $this->isRequestToCorrectCVVOrAVSError;
    }

    public function setIsRequestToCorrectCVVOrAVSError($flag)
    {
        $this->isRequestToCorrectCVVOrAVSError = (bool) $flag;
        return $this;
    }

    public function getSecureVerificationData()
    {
        return $this->secureVerificationData;
    }

    public function setSecureVerificationData($data)
    {
        $this->secureVerificationData = $data;
        return $this;
    }

    public function validate()
    {

    }

    public function serialize()
    {

    }

    public function deserialize($string)
    {

    }
}
