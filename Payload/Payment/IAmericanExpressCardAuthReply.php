<?php
/**
 * Created by PhpStorm.
 * User: smithm5
 * Date: 9/12/14
 * Time: 9:00 AM
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
