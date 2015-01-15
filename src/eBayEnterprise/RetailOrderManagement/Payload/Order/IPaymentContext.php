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

interface IPaymentContext
{
    /**
     * Identifier of the order being placed. Used to uniquely identify the
     * payment session.
     *
     * @return string
     */
    public function getOrderId();

    /**
     * @param string
     * @return self
     */
    public function setOrderId($orderId);

    /**
     * Identifier for the type of card being used. For example, VC for Visa,
     * MC for Master Card.
     *
     * @return string
     */
    public function getTenderType();

    /**
     * @param string
     * @return self
     */
    public function setTenderType($tenderType);

    /**
     * Indicates of the payment account unique id is a token or the raw
     * account number.
     *
     * @return bool
     */
    public function getPanIsToken();

    /**
     * @param bool
     * @return self
     */
    public function setPanIsToken($panIsToken);

    /**
     * Unique identifier for a payment account. The credit card number.
     *
     * restrictions: string with length >= 1 and <= 22
     * @return string
     */
    public function getAccountUniqueId();

    /**
     * @param string
     * @return self
     */
    public function setAccountUniqueId($accountUniqueId);

    /**
     * Id of the request made to the payment service to authorize the payment.
     *
     * restrictions: optional, string with length >= 1 and <= 40
     * @return string
     */
    public function getPaymentRequestId();

    /**
     * @param string
     * @return self
     */
    public function setPaymentRequestId($paymentRequestId);
}
