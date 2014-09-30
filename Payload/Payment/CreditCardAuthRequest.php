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
    /** @var string **/
    protected $requestId;
    /** @var string **/
    protected $orderId;
    /** @var bool **/
    protected $panIsToken;
    /** @var string **/
    protected $cardNumber;
    /** @var DateTime **/
    protected $expirationDate;
    /** @var string **/
    protected $cardSecurityCode;
    /** @var float **/
    protected $amount;
    /** @var string **/
    protected $currencyCode;
    /** @var string **/
    protected $billingFirstName;
    /** @var string **/
    protected $billingLastName;
    /** @var string **/
    protected $billingPhone;
    /** @var string **/
    protected $billingLines;
    /** @var string **/
    protected $billingCity;
    /** @var string **/
    protected $billingMainDivision;
    /** @var string **/
    protected $billingCountryCode;
    /** @var string **/
    protected $billingPostalCode;
    /** @var string **/
    protected $customerEmail;
    /** @var string **/
    protected $customerIpAddress;
    /** @var string **/
    protected $shipToFirstName;
    /** @var string **/
    protected $shipToLastName;
    /** @var string **/
    protected $shipToPhone;
    /** @var string **/
    protected $shipToLines;
    /** @var string **/
    protected $shipToCity;
    /** @var string **/
    protected $shipToMainDivision;
    /** @var string **/
    protected $shipToCountryCode;
    /** @var string **/
    protected $shipToPostalCode;
    /** @var bool **/
    protected $isRequestToCorrectCVVOrAVSError;
    /** @var string **/
    protected $authenticationAvailable;
    /** @var string **/
    protected $getAuthenticationStatus;
    /** @var string **/
    protected $cavvUcaf;
    /** @var string **/
    protected $transactionId;
    /** @var string **/
    protected $eci;
    /** @var string **/
    protected $payerAuthenticationResponse;

    function getRequestId()
    {
        return $this->requestId;
    }

    function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    function getOrderId()
    {
        return $this->orderId;
    }

    function setOrderId($orderId)
    {
        $this->requestId = $orderId;
        return $this;
    }

    function getPanIsToken()
    {
        return $this->panIsToken;
    }

    function setPanIsToken($isToken)
    {
        $this->requestId = $isToken;
        return $this;
    }

    function getCardNumber()
    {
        return $this->cardNumber;
    }

    function setCardNumber($ccNum)
    {
        $this->requestId = $ccNum;
        return $this;
    }

    function getExpirationDate()
    {
        return $this->expirationDate;
    }

    function setExpirationDate(\DateTime $date)
    {
        $this->requestId = $date;
        return $this;
    }

    function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    function setCardSecurityCode($cvv)
    {
        $this->requestId = $cvv;
        return $this;
    }

    function getAmount()
    {
        return $this->amount;
    }

    function setAmount($amount)
    {
        $this->requestId = $amount;
        return $this;
    }

    function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    function setCurrencyCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    function getEmail()
    {
        return $this->customerEmail;
    }

    function setEmail($email)
    {
        $this->requestId = $email;
        return $this;
    }

    function getIp()
    {
        return $this->customerIpAddress;
    }

    function setIp($ip)
    {
        $this->requestId = $ip;
        return $this;
    }

    function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    function setBillingFirstName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    function getBillingLastName()
    {
        return $this->billingLastName;
    }

    function setBillingLastName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    function getBillingPhone()
    {
        return $this->billingPhone;
    }

    function setBillingPhone($phone)
    {
        $this->requestId = $phone;
        return $this;
    }

    function getBillingLines()
    {
        return $this->billingLines;
    }

    function setBillingLines($lines)
    {
        $this->requestId = $lines;
        return $this;
    }

    function getBillingCity()
    {
        return $this->billingCity;
    }

    function setBillingCity($city)
    {
        $this->requestId = $city;
        return $this;
    }

    function getBillingMainDivision()
    {
        return $this->billingMainDivision;
    }

    function setBillingMainDivision($div)
    {
        $this->requestId = $div;
        return $this;
    }

    function getBillingCountryCode()
    {
        return $this->billingCountryCode;
    }

    function setBillingCountryCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    function getBillingPostalCode()
    {
        return $this->billingPostalCode;
    }

    function setBillingPostalCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    function getShipToFirstName()
    {
        return $this->shipToFirstName;
    }

    function setShipToFirstName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    function getShipToLastName()
    {
        return $this->shipToLastName;
    }

    function setShipToLastName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    function getShipToPhone()
    {
        return $this->shipToPhone;
    }

    function setShipToPhone($phone)
    {
        $this->requestId = $phone;
        return $this;
    }

    function getShipToLines()
    {
        return $this->shipToLines;
    }

    function setShipToLines($lines)
    {
        $this->requestId = $lines;
        return $this;
    }

    function getShipToCity()
    {
        return $this->requestId;
    }

    function setShipToCity($city)
    {
        $this->requestId = $city;
        return $this;
    }

    function getShipToMainDivision()
    {
        return $this->shipToCity;
    }

    function setShipToMainDivision($div)
    {
        $this->requestId = $div;
        return $this;
    }

    function getShipToCountryCode()
    {
        return $this->shipToCountryCode;
    }

    function setShipToCountryCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    function getShipToPostalCode()
    {
        return $this->shipToPostalCode;
    }

    function setShipToPostalCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    function getIsRequestToCorrectCvvOrAvsError()
    {
        return $this->isRequestToCorrectCvvOrAvsError;
    }

    function setIsRequestToCorrectCvvOrAvsError($flag)
    {
        $this->requestId = $flag;
        return $this;
    }

    function getAuthenticationAvailable()
    {
        return $this->authenticationAvailable;
    }

    function setAuthenticationAvailable($token)
    {
        $this->requestId = $token;
        return $this;
    }

    function getAuthenticationStatus()
    {
        return $this->authenticationStatus;
    }

    function setAuthenticationStatus($token)
    {
        $this->requestId = $token;
        return $this;
    }

    function getCavvUcaf()
    {
        return $this->cavvUcaf;
    }

    function setCavvUcaf($data)
    {
        $this->requestId = $data;
        return $this;
    }

    function getTransactionId()
    {
        return $this->transactionId;
    }

    function setTransactionId($id)
    {
        $this->requestId = $id;
        return $this;
    }

    function getEci()
    {
        return $this->eci;
    }

    function setEci($eci)
    {
        $this->requestId = $eci;
        return $this;
    }

    function getPayerAuthenticationResponse()
    {
        return $this->payerAuthenticationResponse;
    }

    function setPayerAuthenticationResponse($response)
    {
        $this->requestId = $response;
        return $this;
    }

    function validate()
    {

    }

    function serialize()
    {

    }

    function deserialize($string)
    {

    }
}