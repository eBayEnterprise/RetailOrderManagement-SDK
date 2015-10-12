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
 * trait TOrderId
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 * @see IOrderId
 */
trait TOrderId
{
    /** @var string */
    protected $orderId;

    /**
     * Create an XML string representing the OrderId nodes
     * @return string
     */
    protected function serializeOrderId()
    {
        return "<OrderId>{$this->xmlEncode($this->getOrderId())}</OrderId>";
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
     * @param string $orderId
     * @return self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $this->cleanString($orderId, 20);
        return $this;
    }

    /**
     * Trim any white space and return the resulting string truncating to $maxLength.
     *
     * Return null if the result is an empty string or not a string
     *
     * @param string $string
     * @param int $maxLength
     * @return string or null
     */
    abstract protected function cleanString($string, $maxLength);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
