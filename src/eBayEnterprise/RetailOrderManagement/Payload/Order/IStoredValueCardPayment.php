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

interface IStoredValueCardPayment extends IPayment, ICardPayment, ICustomAttributeContainer
{
    /**
     * Amount redeemed from the stored value card to apply to the order.
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
     * Time the payment was authorized or card values were redeemed.
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
     * Personal identification number (PIN) associated with the stored value card.
     *
     * restrictions: optional, string with length >= 1 and <= 8
     * @return string
     */
    public function getPin();

    /**
     * @param string
     * @return self
     */
    public function setPin($pin);
}
