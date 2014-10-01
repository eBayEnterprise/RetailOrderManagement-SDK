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
 * Interface ICreditCardAuthReply The Reply Message for the Credit Card Authorization Operation
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface ICreditCardAuthReply extends IPayload
{
    /**
     * A unique identifier for the order
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     *
     * xsd restrictions: 1-20 characters
     * @return string
     */
    function getOrderId();
    /**
     * Either a raw PAN or a token representing a PAN.
     *
     * @return string
     */
    function getPaymentAccountUniqueId();
    /**
     * Indicates if the Payment Account Number (PAN) is the actual number, or a representation of the number.
     *
     * @return bool
     */
    function getPanIsToken();
    /**
     * Response code of the credit card authorization. This includes approval, timeout, and several decline codes.
     * Please see supporting documentation for a full list of these codes.
     *
     * @return string
     */
     function getAuthorizationResponseCode();
    /**
     * Authorization Code returned by the payment processor upon a successful credit card auth.
     * Any order taken by the Order Service that is paid for by Credit Card MUST have this authorization code.
     *
     * @return string
     */
    function getBankAuthorizationCode();
    /**
     * Payment Processor Response for CVV2 (Card Verification Value) check.
     * For most credit cards, you will get an Approval on the AuthorizationResponseCode,
     * even though CVV2ResponseCode returns a CVV2 failure.
     * You CANNOT accept an order where CVV2ResponseCode returns a CVV2 failure code.
     * Please see supporting documentation for a full list of these codes.
     *
     * @return string
     */
    function getCVV2ResponseCode();
    /**
     * Payment Processor Response for the Address Verification System check.
     * For most credit cards, you will get an Approval on the AuthorizationResponseCode, even
     * though AVSResponseCode returns an AVS failure code.  That said, it is typically considered a significant fraud
     * risk to accept an order where AVSResponseCode returns an AVS failure code.
     * Please see supporting documentation for a full list of these codes.
     *
     * @return string
     */
    function getAVSResponseCode();
    /**
     * Response code for customer phone number verification (only applies to Amex auths).  This data should be
     * included in the OrderCreateRequest for Orders paid for with Amex to support downstream fraud processing.
     * @return string
     */
    function getPhoneResponseCode();
    /**
     * Response code for customer name verification (only applies to Amex auths). This data should be
     * included in the OrderCreateRequest for Orders paid for with Amex to support downstream fraud processing.
     *
     * @return string
     */
    function getNameResponseCode();
    /**
     * Response code for customer email verification (only applies to Amex auths). This data should be
     * included in the OrderCreateRequest for Orders paid for with Amex to support downstream fraud processing.
     *
     * @return string
     */
    function getEmailResponseCode();
    /**
     * The amount authorized by the credit card processor.
     * Includes a required attribute for a three character ISO currency code.
     *
     * @return float
     */
    function getAmountAuthorized();
    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    function getCurrencyCode();
    /**
     * Was the credit card auth an unqualified success - no errors or failed response codes.
     * @return bool
     */
    function getIsAuthSuccessful();
    /**
     * Can the credit card auth reply be accepted.
     * True if the reply was successful or the request reported a timeout.
     * @return bool
     */
    function getIsAuthAcceptable();
    /**
     * Authorization response code acceptable to send to the OMS.
     * Only valid values for the OMS are "APPROVED" or "TIMEOUT".
     * @return string
     */
    function getResponseCode();
}
