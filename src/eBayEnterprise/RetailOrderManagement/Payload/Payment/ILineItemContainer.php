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
 * Interface ILineItemContainer
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface ILineItemContainer
{
    const ITERABLE_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable';

    /**
     * Get an iterable of the line items for this container.
     *
     * @return ILineItemIterable
     */
    public function getLineItems();

    /**
     * @param ILineItemIterable
     *
     * @return self
     */
    public function setLineItems(ILineItemIterable $items);

    /**
     * Total amount for all line items excluding shipping and tax; calculation works as follows
     * LineItemsTotal = First-LineItem-Quantity * First-LineItem--Amount + next one;
     * PayPal validates above calculation and throws error message for incorrect line items total;
     * Value must be >= 0 when there are line items in the container
     *
     * @return float
     */
    public function getLineItemsTotal();

    /**
     * @param float
     * @return self
     */
    public function setLineItemsTotal($amount);

    /**
     * Total shipping amount for all line items.
     * Value must be >= 0 when there are line items in the container
     *
     * @return float
     */
    public function getShippingTotal();

    /**
     * @param float
     * @return self
     */
    public function setShippingTotal($amount);

    /**
     * Total tax amount for all line items.
     * Value must be >= 0 when there are line items in the container
     *
     * @return float
     */
    public function getTaxTotal();

    /**
     * @param float
     * @return self
     */
    public function setTaxTotal($amount);

    /**
     * This field requires a valid currency code when there are line items.
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($code);

    /**
     * calculate the line items total
     * @return float
     */
    public function calculateLineItemsTotal();
}
