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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ILineItem extends IPayload
{
    const ROOT_NODE = 'LineItem';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Line item name like product title.
     *
     * @return string
     */
    public function getName();

    /**
     * @param string
     * @return self
     */
    public function setName($name);

    /**
     * Sequence number of current line item in cart if available.
     *
     * @return string
     */
    public function getSequenceNumber();

    /**
     * @param string
     * @return self
     */
    public function setSequenceNumber($num);

    /**
     * Quantity for this line item.
     *
     * @return int
     */
    public function getQuantity();

    /**
     * @param int
     * @return self
     */
    public function setQuantity($quantity);

    /**
     * Unit price amount for a line item.
     *
     * @return float
     */
    public function getUnitAmount();

    /**
     * @param float
     * @return self
     */
    public function setUnitAmount($amount);

    /**
     * ISO 4217:2008 code that represents the currency for the unit amount.
     *
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($code);
}
