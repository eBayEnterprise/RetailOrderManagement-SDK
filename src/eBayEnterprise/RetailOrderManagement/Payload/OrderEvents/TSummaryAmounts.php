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

trait TSummaryAmounts
{
    /** @var float */
    protected $totalAmount;
    /** @var float */
    protected $taxAmount;
    /** @var float */
    protected $subtotalAmount;
    /** @var float */
    protected $dutyAmount;
    /** @var float */
    protected $feesAmount;
    /** @var float */
    protected $discountAmount;

    /**
     * Grand total
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }
    /**
     * @param float
     * @return self
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $this->sanitizeAmount($totalAmount);
        return $this;
    }
    /**
     * Tax amount for the order
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }
    /**
     * @param float
     * @return self
     */
    public function setTaxAmount($taxAmount)
    {
        $this->taxAmount = $this->sanitizeAmount($taxAmount);
        return $this;
    }
    /**
     * Order subtotal
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getSubtotalAmount()
    {
        return $this->subtotalAmount;
    }
    /**
     * @param float
     * @return self
     */
    public function setSubtotalAmount($subtotalAmount)
    {
        $this->subtotalAmount = $this->sanitizeAmount($subtotalAmount);
        return $this;
    }
    /**
     * Duty amount for the order
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getDutyAmount()
    {
        return $this->dutyAmount;
    }
    /**
     * @param float
     * @return self
     */
    public function setDutyAmount($dutyAmount)
    {
        $this->dutyAmount = $this->sanitizeAmount($dutyAmount);
        return $this;
    }
    /**
     * Fees amount for the order
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getFeesAmount()
    {
        return $this->feesAmount;
    }
    /**
     * @param float
     * @return self
     */
    public function setFeesAmount($feesAmount)
    {
        $this->feesAmount = $this->sanitizeAmount($feesAmount);
        return $this;
    }
    /**
     * Discount amount for the order.
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }
    /**
     * @param float
     * @return self
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $this->sanitizeAmount($discountAmount);
        return $this;
    }

    /**
     * ensure the amount is rounded to two decimal places.
     *
     * @param  mixed any numeric value
     * @return float|null rounded to 2 places, null if amount is not numeric
     */
    abstract protected function sanitizeAmount($amount);
}
