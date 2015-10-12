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

trait TPaymentContext
{
    /** @var string */
    protected $orderId;
    /** @var string */
    protected $tenderType;
    /** @var bool */
    protected $panIsToken;
    /** @var string */
    protected $accountUniqueId;
    /** @var string */
    protected $paymentRequestId;

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }

    public function getTenderType()
    {
        return $this->tenderType;
    }

    public function setTenderType($tenderType)
    {
        $this->tenderType = $tenderType;
        return $this;
    }

    public function getPanIsToken()
    {
        return $this->panIsToken;
    }

    public function setPanIsToken($panIsToken)
    {
        $this->panIsToken = $panIsToken;
        return $this;
    }

    public function getAccountUniqueId()
    {
        return $this->accountUniqueId;
    }

    public function setAccountUniqueId($accountUniqueId)
    {
        $this->accountUniqueId = $accountUniqueId;
        return $this;
    }

    public function getPaymentRequestId()
    {
        return $this->paymentRequestId;
    }

    public function setPaymentRequestId($paymentRequestId)
    {
        $this->paymentRequestId = $paymentRequestId;
        return $this;
    }

    /**
     * Build an XML serialization of the payment context.
     *
     * @return string
     */
    protected function serializePaymentContext()
    {
        return '<PaymentContext>'
            . "<PaymentSessionId>{$this->xmlEncode($this->getOrderId())}</PaymentSessionId>"
            . "<TenderType>{$this->xmlEncode($this->getTenderType())}</TenderType>"
            . "<PaymentAccountUniqueId isToken='{$this->convertBooleanToString($this->getPanIsToken())}'>"
            . $this->xmlEncode($this->getAccountUniqueId())
            . '</PaymentAccountUniqueId>'
            . '</PaymentContext>';
    }

    /**
     * Build an XML serialization of the payment request id if one exists.
     *
     * @return string
     */
    protected function serializePaymentRequestId()
    {
        return $this->serializeOptionalXmlEncodedValue('PaymentRequestId', $this->getPaymentRequestId());
    }

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
