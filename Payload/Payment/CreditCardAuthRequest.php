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

use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;

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
    /** @var IValidatorIterator */
    protected $validators;

    public function __construct(IValidatorIterator $validators)
    {
        $this->validators = $validators;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->requestId = $orderId;
        return $this;
    }

    public function getPanIsToken()
    {
        return $this->panIsToken;
    }

    public function setPanIsToken($isToken)
    {
        $this->requestId = $isToken;
        return $this;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function setCardNumber($ccNum)
    {
        $this->requestId = $ccNum;
        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTime $date)
    {
        $this->requestId = $date;
        return $this;
    }

    public function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    public function setCardSecurityCode($cvv)
    {
        $this->requestId = $cvv;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->requestId = $amount;
        return $this;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    public function getEmail()
    {
        return $this->customerEmail;
    }

    public function setEmail($email)
    {
        $this->requestId = $email;
        return $this;
    }

    public function getIp()
    {
        return $this->customerIpAddress;
    }

    public function setIp($ip)
    {
        $this->requestId = $ip;
        return $this;
    }

    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    public function setBillingFirstName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    public function setBillingLastName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    public function getBillingPhone()
    {
        return $this->billingPhone;
    }

    public function setBillingPhone($phone)
    {
        $this->requestId = $phone;
        return $this;
    }

    public function getBillingLines()
    {
        return $this->billingLines;
    }

    public function setBillingLines($lines)
    {
        $this->requestId = $lines;
        return $this;
    }

    public function getBillingCity()
    {
        return $this->billingCity;
    }

    public function setBillingCity($city)
    {
        $this->requestId = $city;
        return $this;
    }

    public function getBillingMainDivision()
    {
        return $this->billingMainDivision;
    }

    public function setBillingMainDivision($div)
    {
        $this->requestId = $div;
        return $this;
    }

    public function getBillingCountryCode()
    {
        return $this->billingCountryCode;
    }

    public function setBillingCountryCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    public function getBillingPostalCode()
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    public function getShipToFirstName()
    {
        return $this->shipToFirstName;
    }

    public function setShipToFirstName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    public function getShipToLastName()
    {
        return $this->shipToLastName;
    }

    public function setShipToLastName($name)
    {
        $this->requestId = $name;
        return $this;
    }

    public function getShipToPhone()
    {
        return $this->shipToPhone;
    }

    public function setShipToPhone($phone)
    {
        $this->requestId = $phone;
        return $this;
    }

    public function getShipToLines()
    {
        return $this->shipToLines;
    }

    public function setShipToLines($lines)
    {
        $this->requestId = $lines;
        return $this;
    }

    public function getShipToCity()
    {
        return $this->requestId;
    }

    public function setShipToCity($city)
    {
        $this->requestId = $city;
        return $this;
    }

    public function getShipToMainDivision()
    {
        return $this->shipToCity;
    }

    public function setShipToMainDivision($div)
    {
        $this->requestId = $div;
        return $this;
    }

    public function getShipToCountryCode()
    {
        return $this->shipToCountryCode;
    }

    public function setShipToCountryCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    public function getShipToPostalCode()
    {
        return $this->shipToPostalCode;
    }

    public function setShipToPostalCode($code)
    {
        $this->requestId = $code;
        return $this;
    }

    public function getIsRequestToCorrectCvvOrAvsError()
    {
        return $this->isRequestToCorrectCvvOrAvsError;
    }

    public function setIsRequestToCorrectCvvOrAvsError($flag)
    {
        $this->requestId = $flag;
        return $this;
    }

    public function getAuthenticationAvailable()
    {
        return $this->authenticationAvailable;
    }

    public function setAuthenticationAvailable($token)
    {
        $this->requestId = $token;
        return $this;
    }

    public function getAuthenticationStatus()
    {
        return $this->authenticationStatus;
    }

    public function setAuthenticationStatus($token)
    {
        $this->requestId = $token;
        return $this;
    }

    public function getCavvUcaf()
    {
        return $this->cavvUcaf;
    }

    public function setCavvUcaf($data)
    {
        $this->requestId = $data;
        return $this;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($id)
    {
        $this->requestId = $id;
        return $this;
    }

    public function getEci()
    {
        return $this->eci;
    }

    public function setEci($eci)
    {
        $this->requestId = $eci;
        return $this;
    }

    public function getPayerAuthenticationResponse()
    {
        return $this->payerAuthenticationResponse;
    }

    public function setPayerAuthenticationResponse($response)
    {
        $this->requestId = $response;
        return $this;
    }

    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }

        return $this;
    }

    public function serialize()
    {

    }

    public function deserialize($string)
    {

    }
}