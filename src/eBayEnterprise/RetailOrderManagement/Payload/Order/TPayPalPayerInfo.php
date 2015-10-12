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

trait TPayPalPayerInfo
{
    /** @var string */
    protected $payPalPayerId;
    /** @var string */
    protected $payPalPayerStatus;
    /** @var string */
    protected $payPalAddressStatus;

    public function getPayPalPayerId()
    {
        return $this->payPalPayerId;
    }

    public function setPayPalPayerId($payPalPayerId)
    {
        $this->payPalPayerId = $this->cleanString($payPalPayerId, 50);
        return $this;
    }

    public function getPayPalPayerStatus()
    {
        return $this->payPalPayerStatus;
    }

    public function setPayPalPayerStatus($payPalPayerStatus)
    {
        $this->payPalPayerStatus = $this->cleanString($payPalPayerStatus, 50);
        return $this;
    }

    public function getPayPalAddressStatus()
    {
        return $this->payPalAddressStatus;
    }

    public function setPayPalAddressStatus($payPalAddressStatus)
    {
        $this->payPalAddressStatus = $this->cleanString($payPalAddressStatus, 50);
        return $this;
    }

    /**
     * If there is any PayPal payer info for the request, return a serialization
     * of the PayPal payer info.
     *
     * @return string
     */
    protected function serializePayPalPayerInfo()
    {
        if ($this->getPayPalPayerId() || $this->getPayPalPayerStatus() || $this->getPayPalAddressStatus()) {
            return '<PayPalPayerInfo>'
                . $this->serializeOptionalXmlEncodedValue('PayPalPayerID', $this->getPayPalPayerId())
                . $this->serializeOptionalXmlEncodedValue('PayPalPayerStatus', $this->getPayPalPayerStatus())
                . $this->serializeOptionalXmlEncodedValue('PayPalAddressStatus', $this->getPayPalAddressStatus())
                . '</PayPalPayerInfo>';
        }
        return '';
    }
}
