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

trait TProductPrice
{
    /** @var float */
    protected $amount;
    /** @var float */
    protected $remainder;
    /** @var float */
    protected $unitPrice;

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    public function getRemainder()
    {
        return $this->remainder;
    }

    public function setRemainder($remainder)
    {
        $this->remainder = $this->sanitizeAmount($remainder);
        return $this;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $this->sanitizeAmount($unitPrice);
        return $this;
    }

    protected function serializeProductPrice()
    {
        return '<Pricing>'
            . $this->serializePriceAmount()
            . ($this->getUnitPrice() ? $this->serializeAmount('UnitPrice', $this->getUnitPrice()) : '')
            . '</Pricing>';
    }

    protected function serializePriceAmount()
    {
        $amount = $this->getAmount();
        $remainder = $this->getRemainder();
        $remainderAttr = $remainder ? 'remainder="' . $this->formatAmount($remainder) . '"' : '';
        return $amount
            ? sprintf('<Amount %s>%s</Amount>', $remainderAttr, $this->formatAmount($amount))
            : '';
    }

    /**
     * ensure the amount is rounded to two decimal places.
     *
     * @param mixed any numeric value
     * @return float|null rounded to 2 places, null if amount is not numeric
     */
    abstract protected function sanitizeAmount($amount);

    /**
     * serialize an amount as xml without the currency code
     *
     * @param string name of element containing the amount
     * @param mixed amount to serialize
     * @return string
     */
    abstract protected function serializeAmount($elementName, $amount);
}
