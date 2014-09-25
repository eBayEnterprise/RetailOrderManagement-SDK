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
    /**
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * xsd restrictions: 1-40 characters
     * @return string
     */
    function getRequestId();
    /**
     * @param string $requestId
     * @return self
     */
    function setRequestId($requestId);
    /**
     * A unique identifier for the order
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     *
     * xsd restrictions: 1-20 characters
     * @return string
     */
    function getOrderId();
    /**
     * @param string $orderId
     * @return self
     */
    function setOrderId($orderId);
    /**
     * Indicates if the Payment Account Number (PAN) is the actual number, or a representation of the number.
     *
     * @return bool true if the PAN is a token, false if it's the actual number
     */
    function getPanIsToken();
    /**
     * @param bool $isToken
     * @return self
     */
    function setPanIsToken($isToken);
    /**
     * Either a tokenized or plain credit card number.
     *
     * xsd restrictions: 1-22 characters
     * @see get/setPanIsToken
     * @return string
     */
    function getCardNumber();
    /**
     * @param string $ccNum
     * @return self
     */
    function setCardNumber($ccNum);
    /**
     * Expiration date of the credit card.
     *
     * xsd note: xsd:gYearMonth
     * @link http://www.w3.org/TR/xmlschema-2/#gYearMonth
     * @return \DateTime
     */
    function getExpirationDate();
    /**
     * @param \DateTime $date
     * @return self
     */
    function setExpirationDate(\DateTime $date);
    /**
     * The card security code is the number on the back of the credit card
     * that is normally required for authorizations.
     *
     * xsd note: maxLength 4
     *           pattern (\d{3,4})?
     * return string
     */
    function getCardSecurityCode();
    /**
     * @param string $cvv
     * @return self
     */
    function setCardSecurityCode($cvv);
    /**
     * The amount to authorize
     *
     * xsd note: minimum value 0
     *           maximum precision 2 decimal places
     * @return float
     */
    function getAmount();
    /**
     * @param float $amount
     * @return self
     */
    function setAmount($amount);
    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    function getCurrencyCode();
    /**
     * @param string $code
     * @return self
     */
    function setCurrencyCode($code);
    /**
     * E-mail address of the customer making the purchase.
     *
     * xsd restrictions: 1-70 characters
     * xsd pattern: ([a-zA-Z0-9_\-])([a-zA-Z0-9_\-\.]*)@(\[((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}|((([a-zA-Z0-9\-]+)\.)+))([a-zA-Z]{2,}|(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\])
     *
     * @return string
     */
    function getEmail();
    /**
     * @param string $email
     * @return self
     */
    function setEmail($email);
    /**
     * IP Address of the customer making the purchase.
     *
     * xsd pattern: ((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])
     *
     * @return string
     */
    function getIp();
    /**
     * @param string $ip
     * @return self
     */
    function setIp($ip);
    /**
     * First name of the person on the billing address of the credit card
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
     * Last name of the person on the billing address of the credit card
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
     * Billing phone number of the person on the billing address of the credit card
     *
     * @return string
     */
    function getBillingPhone();
    /**
     * @param string $phone
     * @return self
     */
    function setBillingPhone($phone);
    /**
     * The street address and/or suite and building for the billing address of the credit card.
     *
     * Newline-delimited string, at most four lines
     * xsd restriction: 1-70 characters per line
     * @return string
     */
    function getBillingLines();
    /**
     * @param string $lines
     * @return self
     */
    function setBillingLines($lines);
    /**
     * Name of the city for the billing address of the credit card.
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    function getBillingCity();
    /**
     * @param string $city
     * @return self
     */
    function setBillingCity($city);
    /**
     * Typically a two- or three-digit postal abbreviation for the state or province.
     * ISO 3166-2 code is recommended, but not required
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    function getBillingMainDivision();
    /**
     * @param string $div
     * @return self
     */
    function setBillingMainDivision($div);
    /**
     * Two character country code.
     *
     * xsd restriction: 2-40 characters
     * @return string
     */
    function getBillingCountryCode();
    /**
     * @param string $code
     * @return self
     */
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