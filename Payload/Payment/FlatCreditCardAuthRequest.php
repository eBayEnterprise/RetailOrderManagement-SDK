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
use eBayEnterprise\RetailOrderManagement\Payload;

class FlatCreditCardAuthRequest implements IFlatCreditCardAuthRequest
{
    protected $creditCardAuthRequest;
    public function __construct()
    {
        $this->creditCardAuthRequest = new CreditCardAuthRequest();
    }

    function getRequestId()
    {
        return $this->creditCardAuthRequest->getRequestId();
    }

    function setRequestId($id)
    {
        $this->creditCardAuthRequest->setRequestId(new RequestId($id));
        return $this;
    }

    function getOrderId()
    {
        return $this->creditCardAuthRequest->getPaymentContext()->getOrderId();
    }

    function setOrderId($id)
    {
        $this->creditCardAuthRequest->getPaymentContext()->setOrderId(new Payload\Checkout\OrderId($id));
        return $this;
    }

    function getPanIsToken()
    {
        return $this->creditCardAuthRequest->getPaymentContext()->getPaymentAccountUniqueId()->getIsToken();
    }

    function setPanIsToken($flag)
    {
        $this->creditCardAuthRequest->getPaymentContext()->getPaymentAccountUniqueId()->setIsToken($flag);
        return $this;
    }

    function getCardNumber()
    {
        $this->creditCardAuthRequest->getPaymentContext()->getPaymentAccountUniqueId();
    }

    function setCardNumber($pan)
    {
        $isToken = $this->getPanIsToken();
        $this->creditCardAuthRequest->getPaymentContext()->setPaymentAccountUniqueId(new PaymentAccountUniqueId($pan));
        $this->setPanIsToken($isToken);
        return $this;
    }

    function getExpirationDate()
    {
        // TODO: Implement getExpirationDate() method.
    }

    function setExpirationDate(\DateTime $date)
    {
        // TODO: Implement setExpirationDate() method.
    }

    function getCardSecurityCode()
    {
        // TODO: Implement getCardSecurityCode() method.
    }

    function setCardSecurityCode($cvv)
    {
        // TODO: Implement setCardSecurityCode() method.
    }

    function getAmount()
    {
        // TODO: Implement getAmount() method.
    }

    function setAmount($amount)
    {
        // TODO: Implement setAmount() method.
    }

    function getCurrencyCode()
    {
        // TODO: Implement getCurrencyCode() method.
    }

    function setEmail($email)
    {
        // TODO: Implement setEmail() method.
    }

    function getIp()
    {
        // TODO: Implement getIp() method.
    }

    function getBillingFirstName()
    {
        // TODO: Implement getBillingFirstName() method.
    }

    function setBillingFirstName($name)
    {
        // TODO: Implement setBillingFirstName() method.
    }

    function getBillingLastName()
    {
        // TODO: Implement getBillingLastName() method.
    }

    function setBillingLastName($name)
    {
        // TODO: Implement setBillingLastName() method.
    }

    function getBillingPhone()
    {
        // TODO: Implement getBillingPhone() method.
    }

    function setBillingPhone($phone)
    {
        // TODO: Implement setBillingPhone() method.
    }

    function getBillingLine1()
    {
        // TODO: Implement getBillingLine1() method.
    }

    function setBillingLine1($line)
    {
        // TODO: Implement setBillingLine1() method.
    }

    function getBillingLine2()
    {
        // TODO: Implement getBillingLine2() method.
    }

    function setBillingLine2($line)
    {
        // TODO: Implement setBillingLine2() method.
    }

    function getBillingLine3()
    {
        // TODO: Implement getBillingLine3() method.
    }

    function setBillingLine3($line)
    {
        // TODO: Implement setBillingLine3() method.
    }

    function getBillingLine4()
    {
        // TODO: Implement getBillingLine4() method.
    }

    function setBillingLine4($line)
    {
        // TODO: Implement setBillingLine4() method.
    }

    function getBillingCity()
    {
        // TODO: Implement getBillingCity() method.
    }

    function setBillingCity($city)
    {
        // TODO: Implement setBillingCity() method.
    }

    function getBillingMainDivision()
    {
        // TODO: Implement getBillingMainDivision() method.
    }

    function setBillingMainDivision($div)
    {
        // TODO: Implement setBillingMainDivision() method.
    }

    function getBillingCountryCode()
    {
        // TODO: Implement getBillingCountryCode() method.
    }

    function setBillingCountryCode($code)
    {
        // TODO: Implement setBillingCountryCode() method.
    }

    function getBillingPostalCode()
    {
        // TODO: Implement getBillingPostalCode() method.
    }

    function setBillingPostalCode($code)
    {
        // TODO: Implement setBillingPostalCode() method.
    }

    function getShipToFirstName()
    {
        // TODO: Implement getShipToFirstName() method.
    }

    function setShipToFirstName($name)
    {
        // TODO: Implement setShipToFirstName() method.
    }

    function getShipToLastName()
    {
        // TODO: Implement getShipToLastName() method.
    }

    function setShipToLastName($name)
    {
        // TODO: Implement setShipToLastName() method.
    }

    function getShipToPhone()
    {
        // TODO: Implement getShipToPhone() method.
    }

    function setShipToPhone($phone)
    {
        // TODO: Implement setShipToPhone() method.
    }

    function getShipToLine1()
    {
        // TODO: Implement getShipToLine1() method.
    }

    function setShipToLine1($line)
    {
        // TODO: Implement setShipToLine1() method.
    }

    function getShipToLine2()
    {
        // TODO: Implement getShipToLine2() method.
    }

    function setShipToLine2($line)
    {
        // TODO: Implement setShipToLine2() method.
    }

    function getShipToLine3()
    {
        // TODO: Implement getShipToLine3() method.
    }

    function setShipToLine3($line)
    {
        // TODO: Implement setShipToLine3() method.
    }

    function getShipToLine4()
    {
        // TODO: Implement getShipToLine4() method.
    }

    function setShipToLine4($line)
    {
        // TODO: Implement setShipToLine4() method.
    }

    function getShipToCity()
    {
        // TODO: Implement getShipToCity() method.
    }

    function setShipToCity($city)
    {
        // TODO: Implement setShipToCity() method.
    }

    function getShipToMainDivision()
    {
        // TODO: Implement getShipToMainDivision() method.
    }

    function setShipToMainDivision($div)
    {
        // TODO: Implement setShipToMainDivision() method.
    }

    function getShipToCountryCode()
    {
        // TODO: Implement getShipToCountryCode() method.
    }

    function setShipToCountryCode($code)
    {
        // TODO: Implement setShipToCountryCode() method.
    }

    function getShipToPostalCode()
    {
        // TODO: Implement getShipToPostalCode() method.
    }

    function setShipToPostalCode($code)
    {
        // TODO: Implement setShipToPostalCode() method.
    }

    function getIsRequestToCorrectCvvOrAvsError()
    {
        // TODO: Implement getIsRequestToCorrectCvvOrAvsError() method.
    }

    function setIsRequestToCorrectCvvOrAvsError($flag)
    {
        // TODO: Implement setIsRequestToCorrectCvvOrAvsError() method.
    }

    function getAuthenticationAvailable()
    {
        // TODO: Implement getAuthenticationAvailable() method.
    }

    function setAuthenticationAvailable($token)
    {
        // TODO: Implement setAuthenticationAvailable() method.
    }

    function getAuthenticationStatus()
    {
        // TODO: Implement getAuthenticationStatus() method.
    }

    function setAuthenticationStatus($token)
    {
        // TODO: Implement setAuthenticationStatus() method.
    }

    function getCavvUcaf()
    {
        // TODO: Implement getCavvUcaf() method.
    }

    function setCavvUcaf($data)
    {
        // TODO: Implement setCavvUcaf() method.
    }

    function getTransactionId()
    {
        // TODO: Implement getTransactionId() method.
    }

    function setTransactionId($id)
    {
        // TODO: Implement setTransactionId() method.
    }

    function getEci()
    {
        // TODO: Implement getEci() method.
    }

    function setEci($eci)
    {
        // TODO: Implement setEci() method.
    }

    function getPayerAuthenticationResponse()
    {
        // TODO: Implement getPayerAuthenticationResponse() method.
    }

    function setPayerAuthenticationResponse($response)
    {
        // TODO: Implement setPayerAuthenticationResponse() method.
    }
}