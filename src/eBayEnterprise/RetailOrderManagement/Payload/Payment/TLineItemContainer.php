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

trait TLineItemContainer
{
    /** @var ILineItemContainer * */
    protected $lineItems;
    /** @var float */
    protected $shippingTotal;
    /** @var float */
    protected $lineItemsTotal;
    /** @var float */
    protected $taxTotal;

    /**
     * @param string
     * @return self
     */
    abstract public function setCurrencyCode($code);

    /**
     * @return string
     */
    abstract public function getCurrencyCode();

    /**
     * Get an iterable of the line items for this container.
     *
     * @return ILineItemIterable
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @param ILineItemIterable
     * @return self
     */
    public function setLineItems(ILineItemIterable $items)
    {
        $this->lineItems = $items;
        return $this;
    }

    /**
     * calculate and set the line items total.
     * @return self
     */
    public function calculateLineItemsTotal()
    {
        $total = 0.0;
        foreach ($this->getLineItems() as $item) {
            $total += $item->getUnitAmount() * $item->getQuantity();
        }
        $this->setLineItemsTotal($total);
        return $this;
    }

    /**
     * Total amount for all line items excluding shipping and tax; calculation works as follows
     * LineItemsTotal = First-LineItem-Quantity * First-LineItem--Amount + next one;
     * PayPal validates above calculation and throws error message for incorrect line items total;
     * LineItemsTotal must always be greater than 0.
     *
     * @return float
     */
    public function getLineItemsTotal()
    {
        return $this->lineItemsTotal;
    }

    /**
     * @param float
     * @return self
     */
    public function setLineItemsTotal($amount)
    {
        $this->lineItemsTotal = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * Total shipping amount for all line items.
     *
     * @return float
     */
    public function getShippingTotal()
    {
        return $this->shippingTotal;
    }

    /**
     * @param float
     * @return self
     */
    public function setShippingTotal($amount)
    {
        $this->shippingTotal = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * Total tax amount for all line items.
     *
     * @return float
     */
    public function getTaxTotal()
    {
        return $this->taxTotal;
    }

    /**
     * @param float
     * @return self
     */
    public function setTaxTotal($amount)
    {
        $this->taxTotal = $this->sanitizeAmount($amount);
        return $this;
    }

    abstract protected function serializeCurrencyAmount($nodeName, $amount, $currencyCode);
    abstract protected function sanitizeAmount($amount);

    /**
     * Serialization of the container and its contents
     * @return string
     */
    protected function serializeLineItemsContainer()
    {
        return count($this->getLineItems()) ? $this->getLineItemContents() : '';
    }

    /**
     * Serialize Line Items contents in XML.
     * @return string
     */
    protected function getLineItemContents()
    {
        return '<LineItems>'
            . $this->serializeLineItemsTotal()
            . $this->serializeShippingTotal()
            . $this->serializeTaxTotal()
            . $this->serializeLineItems()
            . '</LineItems>';
    }

    /**
     * serialize the line items
     *
     * @return string
     */
    protected function serializeLineItems()
    {
        return $this->getLineItems()->serialize();
    }

    /**
     * serialize the data for the LineItemsTotal element
     * @return string
     */
    protected function serializeLineItemsTotal()
    {
        return $this->serializeCurrencyAmount(
            'LineItemsTotal',
            $this->getLineItemsTotal(),
            $this->xmlEncode($this->getCurrencyCode())
        );
    }

    /**
     * serialize the data for the ShippingTotal element
     * @return string
     */
    protected function serializeShippingTotal()
    {
        return $this->serializeCurrencyAmount('ShippingTotal', $this->getShippingTotal(), $this->xmlEncode($this->getCurrencyCode()));
    }

    /**
     * serialize the data for the TaxTotal element
     * @return string
     */
    protected function serializeTaxTotal()
    {
        return $this->serializeCurrencyAmount('TaxTotal', $this->getTaxTotal(), $this->xmlEncode($this->getCurrencyCode()));
    }

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
