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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

interface ISummaryAmounts
{
    /**
     * Grand total
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getTotalAmount();
    /**
     * @param float
     * @return self
     */
    public function setTotalAmount($totalAmount);
    /**
     * Tax amount for the order
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getTaxAmount();
    /**
     * @param float
     * @return self
     */
    public function setTaxAmount($taxAmount);
    /**
     * Order subtotal
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getSubtotalAmount();
    /**
     * @param float
     * @return self
     */
    public function setSubtotalAmount($subtotalAmount);
    /**
     * Duty amount for the order
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getDutyAmount();
    /**
     * @param float
     * @return self
     */
    public function setDutyAmount($dutyAmount);
    /**
     * Fees amount for the order
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getFeesAmount();
    /**
     * @param float
     * @return self
     */
    public function setFeesAmount($feesAmount);
    /**
     * Discount amount for the order.
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getDiscountAmount();
    /**
     * @param float
     * @return self
     */
    public function setDiscountAmount($discountAmount);
}
