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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

interface IPayPalPayerInfo
{
    /**
     * Unique id for the PayPal payer. Provided by PayPal and used for fraud prevention.
     *
     * restrictions: optional, string with length <= 50, required when using a PayPal payment method
     * @return string
     */
    public function getPayPalPayerId();

    /**
     * @param string
     * @return self
     */
    public function setPayPalPayerId($payPalPayerId);

    /**
     * Status of PayPal payer. Provided by PayPal and used for fraud prevention.
     *
     * restrictions: optional, string with length <= 50, required when using a PayPal payment method
     * @return string
     */
    public function getPayPalPayerStatus();

    /**
     * @param string
     * @return self
     */
    public function setPayPalPayerStatus($payPalPayerStatus);

    /**
     * Status of the PayPal payer address. Provided by PayPal and used for fraud prevention.
     *
     * restrictions: optional, string with length <= 50, required when using a PayPal payment method
     * @return string
     */
    public function getPayPalAddressStatus();

    /**
     * @param string
     * @return self
     */
    public function setPayPalAddressStatus($payPalAddressStatus);
}
