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
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IOrderId;

class PaymentContext implements IPaymentContext
{
    protected $paymentAccountUniqueId;
    protected $orderId;

    /**
     * Either a raw PAN or a token representing a PAN.
     * The type includes an attribute, isToken, to indicate if the PAN is tokenized.
     *
     * @return IPaymentAccountUniqueId
     */
    function getPaymentAccountUniqueId()
    {
        return $this->paymentAccountUniqueId;
    }

    /**
     * @param IPaymentAccountUniqueId $id
     * @return self
     */
    function setPaymentAccountUniqueId(IPaymentAccountUniqueId $id)
    {
        $this->paymentAccountUniqueId = $id;
        return $this;
    }

    /**
     * A unique identifier for the order
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     *
     * @return IOrderId
     */
    function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param IOrderId $id
     * @return self
     */
    function setOrderId(IOrderId $id)
    {
        $this->orderId = $id;
        return $this;
    }
}