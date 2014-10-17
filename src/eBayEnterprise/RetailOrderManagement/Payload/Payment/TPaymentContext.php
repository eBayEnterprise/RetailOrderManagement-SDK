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
 * trait TPaymentContext
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 * @example <PaymentContext><OrderId>{order id}</OrderId>{PaymentAccountUniqueId}</PaymentContext>
 */
trait TPaymentContext
{
    use TPaymentAccountUniqueId;

    /** @var string */
    protected $orderId;

    /**
     * @param string $orderId
     * @return self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $this->cleanString($orderId, 20);
        return $this;
    }

    /**
     * A unique identifier for the order
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     *
     * xsd restrictions: 1-20 characters
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Create an XML string representing the PaymentContext nodes
     * @return string
     */
    protected function serializePaymentContext()
    {
        return sprintf(
            '<PaymentContext><OrderId>%s</OrderId>%s</PaymentContext>',
            $this->getOrderId(),
            $this->serializePaymentAccountUniqueId()
        );
    }
}
