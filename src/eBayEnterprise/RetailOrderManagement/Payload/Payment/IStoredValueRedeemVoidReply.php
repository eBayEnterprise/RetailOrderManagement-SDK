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
 * Interface IStoredValueRedeemVoidReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 */
interface IStoredValueRedeemVoidReply extends IPayload
{
    /**
     * Id of the order.
     *
     * xsd restrictions: 1-20 characters
     * @return int
     */
    public function getOrderId();
    /**
     * Indicates if the account Id is the actual number, or a representation of the number.
     *
     * @return bool true if the PAN is a token, false if it's the actual number
     */
    public function getAccountIdIsToken();
    /**
     * Either a tokenized or plain text stored value account id.
     *
     * xsd restrictions: 1-22 characters
     * @see get/setPanIsToken
     * @return string
     */
    public function getAccountId();
    /**
     * The amount to void.
     *
     * xsd note: eneration
     *           pattern (Fail|Success|Timeout)
     * return string
     */
    public function getResponseCode();
}
