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

interface IPayPalPayment extends IPayment, ICustomAttributeContainer, IPaymentContext
{
    /**
     * Currency amount to be charged to the PayPal account.
     *
     * restrictions: two decimal, non-negative
     * @return float
     */
    public function getAmount();

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);

    /**
     * Currency amount authorized by PayPal to be charged to the account.
     * This value must match the amount expected to be charged to the account.
     *
     * restrictions: two decimal, non-negative
     * @return float
     */
    public function getAmountAuthorized();

    /**
     * @param float
     * @return self
     */
    public function setAmountAuthorized($amountAuthorized);

    /**
     * Time the payment was authorized.
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
     * Response code received from PayPal for the authorization request.
     *
     * restrictions:
     * @return string
     */
    public function getAuthorizationResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setAuthorizationResponseCode($authorizationResponseCode);
}
