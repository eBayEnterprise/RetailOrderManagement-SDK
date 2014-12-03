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
 * Interface IPaymentInfo
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IPaymentInfo
{
    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getPaymentStatus();

    /**
     * @param string
     * @return self
     */
    public function setPaymentStatus($status);

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getPendingReason();

    /**
     * @param string
     * @return self
     */
    public function setPendingReason($reason);

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getReasonCode();

    /**
     * @param string
     * @return self
     */
    public function setReasonCode($code);
}
