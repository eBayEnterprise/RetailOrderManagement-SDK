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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;

interface ICreditCardPayment extends IPayment, ICustomAttributeContainer, ICardPayment
{
    const RESPONSE_CODE_APPROVED = 'APPROVED';
    const RESPONSE_CODE_ACCEPTORDERWITHEXCEPTION = 'AcceptOrderWithException';
    const RESPONSE_CODE_AVS = 'AVS';
    const RESPONSE_CODE_AVSPIN = 'AVSPIN';
    const RESPONSE_CODE_DECLF = 'DECLF';
    const RESPONSE_CODE_DECLR = 'DECLR';
    const RESPONSE_CODE_DECL = 'DECL';
    const RESPONSE_CODE_PAYMENTPROCESSORTIMEOUT = 'PaymentProcessorTimeout';
    const RESPONSE_CODE_PIN = 'PIN';
    const RESPONSE_CODE_NOK = 'NOK';
    const RESPONSE_CODE_OK = 'OK';

    /**
     * Timestamp of when the payment was authorized.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getCreateTimeStamp();

    /**
     * @param DateTime
     * @return self
     */
    public function setCreateTimeStamp(DateTime $createTimeStamp);

    /**
     * Currency amount applied to the card.
     *
     * restrictions: optional, two decimal, non-negative
     * @return float
     */
    public function getAmount();

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);

    /**
     * Payment authorization response code received from the payment service
     * that authorized the payment. Expected to be one of:
     * APPROVED, AcceptOrderWithException, AVS, AVSPIN, DECLF, DECLR, DECL,
     * PaymentProcessorTimeout, PIN, NOK, OK,
     *
     * @return string
     */
    public function getResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setResponseCode($responseCode);

    /**
     * Bank authorization code received from the payment service that authorized
     * the payment.
     *
     * @return string
     */
    public function getBankAuthorizationCode();

    /**
     * @param string
     * @return self
     */
    public function setBankAuthorizationCode($bankAuthorizationCode);

    /**
     * CVV2 response code received from the payment service that authorized
     * the payment.
     *
     * @return string
     */
    public function getCVV2ResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setCVV2ResponseCode($cvv2ResponseCode);

    /**
     * Address validation service (AVS) response code received from the
     * payment service that authorized the payment.
     *
     * @return string
     */
    public function getAVSResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setAVSResponseCode($avsResponseCode);

    /**
     * Response code for customer phone number received from the payment
     * service that authorized the payment.
     *
     * restrictions: optional
     * @return string
     */
    public function getPhoneResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setPhoneResponseCode($phoneResponseCode);

    /**
     * Customer name verification response code received from the payment
     * service that authorized the payment.
     *
     * restrictions: optional
     * @return string
     */
    public function getNameResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setNameResponseCode($nameResponseCode);

    /**
     * Customer email verification response code received from the payment
     * service that authorized the payment.
     *
     * restrictions: optional
     * @return string
     */
    public function getEmailResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setEmailResponseCode($emailResponseCode);

    /**
     * Description of the extended authorization response code received from
     * the payment service that authorized the payment.
     *
     * restrictions: required if an extended auth reason code is given
     * @return string
     */
    public function getExtendedAuthDescription();

    /**
     * @param string
     * @return self
     */
    public function setExtendedAuthDescription($extendedAuthDescription);

    /**
     * Code indicating if the payment authorization has been approved and, if
     * not, why it was rejected. Received from the payment service that
     * authorized the payment.
     *
     * restrictions: required if an extended auth response code description is given
     * @return string
     */
    public function getExtendedAuthReasonCode();

    /**
     * @param string
     * @return self
     */
    public function setExtendedAuthReasonCode($extendedAuthReasonCode);

    /**
     * Currency amount authorized for the transaction.
     *
     * restrictions: optional, two decimal, non-negative
     * @return float
     */
    public function getAmountAuthorized();

    /**
     * @param float
     * @return self
     */
    public function setAmountAuthorized($amountAuthorized);

    /**
     * Month and year of card expiration date.
     *
     * restrictions: optional, date in YYYY-MM format
     * @return DateTime
     */
    public function getExpirationDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setExpirationDate(DateTime $expirationDate);

    /**
     * Start date for credit card account. Required to authorize some legacy
     * Maestro UK cards.
     *
     * restrictions: optional, date in YYYY-MM format
     * @return DateTime
     */
    public function getStartDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setStartDate(DateTime $startDate);

    /**
     * Card issue number. Required to authorized some legacy Maestro UK cards.
     *
     * restrictions: optional, string
     * @return string
     */
    public function getIssueNumber();

    /**
     * @param string
     * @return self
     */
    public function setIssueNumber($issueNumber);

    /**
     * Used by Verified by VISA (VPAS) transactions only. When used, expected
     * to be one of: "Y", "N", "U"
     *
     * restrictions: optional
     * @return string
     */
    public function getAuthenticationAvailable();

    /**
     * @param string
     * @return self
     */
    public function setAuthenticationAvailable($authenticationAvailable);

    /**
     * Status of the VPAS authentication.
     *
     * restrictions: optional, one of "Y", "A", "U", "N"
     * @return string
     */
    public function getAuthenticationStatus();

    /**
     * @param string
     * @return self
     */
    public function setAuthenticationStatus($authenticationStatus);

    /**
     * 3D Secure CAVV UCAF data for VPAS authentication.
     *
     * restrictions: optional, string with length <= 64)
     * @return string
     */
    public function getCavvUcaf();

    /**
     * @param string
     * @return self
     */
    public function setCavvUcaf($cavvUcaf);

    /**
     * 3D secure VBV transaction ID for VPAS authentication.
     *
     * restrictions: optional, string with length <= 64)
     * @return string
     */
    public function getTransactionId();

    /**
     * @param string
     * @return self
     */
    public function setTransactionId($transactionId);

    /**
     * 3D secure ECI for VPAS authentication.
     *
     * restrictions: optional
     * @return string
     */
    public function getECI();

    /**
     * @param string
     * @return self
     */
    public function setECI($eci);

    /**
     * The authentication result of the bank access control server. Will be
     * provided by the payment service that authenticated the payment.
     *
     * restrictions: string with length <= 10000
     * @return string
     */
    public function getPayerAuthenticationResponse();

    /**
     * @param string
     * @return self
     */
    public function setPayerAuthenticationResponse($payerAuthenticationResponse);

    /**
     * Identifies the purchase plan code for PLCC credit cards.
     *
     * restrictions: optional
     * @return string
     */
    public function getPurchasePlanCode();

    /**
     * @param string
     * @return self
     */
    public function setPurchasePlanCode($purchasePlanCode);

    /**
     * Description of the purchase plan for PLCC credit cards.
     *
     * restrictions: optional
     * @return string
     */
    public function getPurchasePlanDescription();

    /**
     * @param string
     * @return self
     */
    public function setPurchasePlanDescription($purchasePlanDescription);
}
