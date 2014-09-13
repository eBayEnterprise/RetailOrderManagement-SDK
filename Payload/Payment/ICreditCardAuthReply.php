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
use eBayEnterprise\RetailOrderManagement\Payload\ISerializable;

/**
 * Interface ICreditCardAuthReply The Reply Message for the Credit Card Authorization Operation
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface ICreditCardAuthReply extends ISerializable
{
    /**
     * The PaymentContext combines with the tendertype in the URI to uniquely identify a Payment Transaction for an order.
     *
     * @return IPaymentContext
     */
    function getPaymentContext();
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
     * The amount authorized by the credit card processor.
     * Includes a required attribute for a three character ISO currency code.
     *
     * @return IAmount
     */
    function getAmountAuthorized();
}
