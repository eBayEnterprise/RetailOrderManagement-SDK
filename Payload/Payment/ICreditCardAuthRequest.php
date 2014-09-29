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
 * Interface ICreditCardAuthRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 */
interface ICreditCardAuthRequest extends IPayload
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
     * Billing phone number of the person
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
     * The street address and/or suite and building
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
     * Name of the city
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
    /**
     * Typically, the string of letters and/or numbers that more closely
     * specifies the delivery area than just the City component alone,
     * for example, the Zip Code in the U.S.
     *
     * xsd restriction: 1-15 characters
     * @return string
     */
    function getBillingPostalCode();
    /**
     * @param string $code
     * @return self
     */
    function setBillingPostalCode($code);
    /**
     * First name of the person on the shipping address of the order
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
     * Last name of the person on the shipping address of the order
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
     * ShipTo phone number of the person
     *
     * @return string
     */
    function getShipToPhone();
    /**
     * @param string $phone
     * @return self
     */
    function setShipToPhone($phone);
    /**
     * The street address and/or suite and building
     *
     * Newline-delimited string, at most four lines
     * xsd restriction: 1-70 characters per line
     * @return string
     */
    function getShipToLines();
    /**
     * @param string $lines
     * @return self
     */
    function setShipToLines($lines);
    /**
     * Name of the city
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    function getShipToCity();
    /**
     * @param string $city
     * @return self
     */
    function setShipToCity($city);
    /**
     * Typically a two- or three-digit postal abbreviation for the state or province.
     * ISO 3166-2 code is recommended, but not required
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    function getShipToMainDivision();
    /**
     * @param string $div
     * @return self
     */
    function setShipToMainDivision($div);
    /**
     * Two character country code.
     *
     * xsd restriction: 2-40 characters
     * @return string
     */
    function getShipToCountryCode();
    /**
     * @param string $code
     * @return self
     */
    function setShipToCountryCode($code);
    /**
     * Typically, the string of letters and/or numbers that more closely
     * specifies the delivery area than just the City component alone,
     * for example, the Zip Code in the U.S.
     *
     * xsd restriction: 1-15 characters
     * @return string
     */
    function getShipToPostalCode();
    /**
     * @param string $code
     * @return self
     */
    function setShipToPostalCode($code);
    /**
     * Indicates that this is an authorization re-submission to correct AVS or CVV2 error.
     * If set to true, this will process the transaction specifically as an AVS/CVV check.
     * This is important to set correctly, otherwise the cardholder will have
     * their credit card authed multiple times for the full payment amount when correcting
     * AVS/CSC errors.
     *
     * @return bool
     */
    function getIsRequestToCorrectCvvOrAvsError();
    /**
     * @param bool $flag
     * @return self
     */
    function setIsRequestToCorrectCvvOrAvsError($flag);
    /**
     * Verified by VISA (VPAS) e-commerce transactions only.
     * Verify Enrollment Response from the VERes message,
     * returned to the POE from the ACS server as a result of a Verify Enrollment Request
     *
     * 'Y' - Card eligible for authentication processing
     * 'N' - Attempted authentication. Card eligible for attempts liability,
     *       but attempts proof is not available from Issuer.
     * 'U' - Unable to process or card not eligible for attempts.
     *
     * @return string
     */
    function getAuthenticationAvailable();
    /**
     * @param string $token
     * @return self
     */
    function setAuthenticationAvailable($token);
    /**
     * Verified by VISA (VPAS) / MasterCard Secure Code (UCAF)
     * Transaction Status: For e-commerce VISA and MasterCard transactions only.
     * Returned in the PARes message from the ACS server in the “Transaction Status” field.
     *
     * 'Y' - Authentication Approved
     * 'A' - Authentication attempted.
     * 'U' - Unable to authenticate (due to technical problems or excluded card type).
     * 'N' - Authentication failed.
     *
     * @return string
     */
    function getAuthenticationStatus();
    /**
     * @param string $token
     * @return self
     */
    function setAuthenticationStatus($token);
    /**
     * E-commerce Verified by VISA and MasterCard SecureCode transactions only.
     * Data returned in authentication request.
     * For VISA, this field contains CAVV values in upacked, displayable format (0-9, A-F).
     * For MasterCard, this field contains the UCAF data in upacked, displayable base-64 format
     * (A-Z, a-z, 0-9, +, /, -).
     *
     * xsd restriction: maxLength 64 characters
     *
     * @return string
     */
    function getCavvUcaf();
    /**
     * @param string $data
     * @return self
     */
    function setCavvUcaf($data);
    /**
     * E-commerce Verified by VISA transactions only.
     * XID data returned from authentication request
     * in upacked, displayable format (0-9, AF).
     *
     * xsd restriction: maxLength 64 characters
     *
     * @return string
     */
    function getTransactionId();
    /**
     * @param string $id
     * @return self
     */
    function setTransactionId($id);
    /**
     * @return string
     */
    function getEci();
    /**
     * @param string $eci
     * @return self
     */
    function setEci($eci);
    /**
     * The Issuer Bank ACS (Access Control Server) authenticates the cardholder.
     * The authentication result is represented by the Payer Authentication Response (PARes)
     * generated by the Card Issuer ACS.
     *
     * xsd restriction: maxLength 10000 characters
     *
     * @return string
     */
    function getPayerAuthenticationResponse();
    /**
     * @param string $response
     * @return self
     */
    function setPayerAuthenticationResponse($response);
}