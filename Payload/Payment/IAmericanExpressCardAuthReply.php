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

/**
 * A CreditCardAuthReply for American Express cards
 *
 * Interface IAmericanExpressCardAuthReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IAmericanExpressCardAuthReply extends ICreditCardAuthReply
{
    /**
     * Response code for customer phone number verification (only applies to Amex auths).
     * This data should be included in the OrderCreateRequest for Orders paid for with Amex
     * to support downstream fraud processing.
     *
     * @return string
     */
     function getPhoneResponseCode();
    /**
     * Response code for customer name verification (only applies to Amex auths).
     * This data should be included in the OrderCreateRequest for Orders paid for with Amex
     * to support downstream fraud processing.
     *
     * @return string
     */
     function getNameResponseCode();
    /**
     * Response code for customer email verification (only applies to Amex auths).
     * This data should be included in the OrderCreateRequest for Orders paid for with Amex
     * to support downstream fraud processing.
     *
     * @return string
     */
     function getEmailResponseCode();
}
