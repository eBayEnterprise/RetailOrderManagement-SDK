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

trait TPaymentInfo
{
    /** @var string * */
    protected $paymentStatus;
    /** @var string * */
    protected $pendingReason;
    /** @var string * */
    protected $reasonCode;

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param string
     * @return self
     */
    public function setPaymentStatus($status)
    {
        $this->paymentStatus = $status;
        return $this;
    }

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * @param string
     * @return self
     */
    public function setPendingReason($reason)
    {
        $this->pendingReason = $reason;
        return $this;
    }

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setReasonCode($code)
    {
        $this->reasonCode = $code;
        return $this;
    }
}
