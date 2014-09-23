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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

/**
 * Interface IFlatCreditCardAuthRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 * A convenient way to fill out an ICreditCardAuthRequest.
 * @see ICreditCardAuthRequest for details
 */
interface IFlatCreditCardAuthRequest extends IPayload
{
    function getRequestId();
    function setRequestId($id);
    function getOrderId();
    function setOrderId($id);
    function getPanIsToken();
    function setPanIsToken($flag);
    function getCardNumber();
    function setCardNumber($pan);
    function getExpirationDate();
    function setExpirationDate(\DateTime $date);
    function getCardSecurityCode();
    function setCardSecurityCode($cvv);
    function getAmount();
    function setAmount($amount);
    function getCurrencyCode();
    function setEmail($email);
    function getIp();
    function getBillingFirstName();
    function setBillingFirstName($name);
    function getBillingLastName();
    function setBillingLastName($name);
    function getBillingPhone();
    function setBillingPhone($phone);
    function getBillingLine1();
    function setBillingLine1($line);
    function getBillingLine2();
    function setBillingLine2($line);
    function getBillingLine3();
    function setBillingLine3($line);
    function getBillingLine4();
    function setBillingLine4($line);
    function getBillingCity();
    function setBillingCity($city);
    function getBillingMainDivision();
    function setBillingMainDivision($div);
    function getBillingCountryCode();
    function setBillingCountryCode($code);
    function getBillingPostalCode();
    function setBillingPostalCode($code);
    function getShipToFirstName();
    function setShipToFirstName($name);
    function getShipToLastName();
    function setShipToLastName($name);
    function getShipToPhone();
    function setShipToPhone($phone);
    function getShipToLine1();
    function setShipToLine1($line);
    function getShipToLine2();
    function setShipToLine2($line);
    function getShipToLine3();
    function setShipToLine3($line);
    function getShipToLine4();
    function setShipToLine4($line);
    function getShipToCity();
    function setShipToCity($city);
    function getShipToMainDivision();
    function setShipToMainDivision($div);
    function getShipToCountryCode();
    function setShipToCountryCode($code);
    function getShipToPostalCode();
    function setShipToPostalCode($code);
    function getIsRequestToCorrectCvvOrAvsError();
    function setIsRequestToCorrectCvvOrAvsError($flag);
    function getAuthenticationAvailable();
    function setAuthenticationAvailable($token);
    function getAuthenticationStatus();
    function setAuthenticationStatus($token);
    function getCavvUcaf();
    function setCavvUcaf($data);
    function getTransactionId();
    function setTransactionId($id);
    function getEci();
    function setEci($eci);
    function getPayerAuthenticationResponse();
    function setPayerAuthenticationResponse($response);
}