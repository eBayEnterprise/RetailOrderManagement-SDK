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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IOrderShipped extends IPayload, IOrderEvent, ICustomer
{
    const ROOT_NODE = 'OrderShipped';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = 'Order-Shipping-Event-1.0.xsd';
    const SHIPPED_ITEM_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IShippedItemIterable';
    const PAYMENTS_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IPaymentIterable';
    const TAXES_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ITaxDescriptionIterable';

    /**
     * Order id generate by the webstore.
     *
     * xsd restrictions: 1-24 characters
     * @return string
     */
    public function getOrderId();
    /**
     * @param string
     * @return self
     */
    public function setOrderId($orderId);
    /**
     * ROM store id in which the order was placed
     *
     * @return string
     */
    public function getStoreId();
    /**
     * @param string
     * @return self
     */
    public function setStoreId($storeId);
    /**
     * Total amount of the order.
     *
     * xsd restrictions: two decimal float, min value 0.00
     * @return float
     */
    public function getTotalAmount();
    /**
     * @param float
     * @return self
     */
    public function setTotalAmount($totalAmount);
    /**
     * Currency code for the currency being used for amounts.
     *
     * xsd restriction: 3 character currency code
     * @return string
     */
    public function getCurrencyCode();
    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($currencyCode);
    /**
     * Symbol to display to represent the currency type.
     *
     * @return string
     */
    public function getCurrencySymbol();
    /**
     * @param string
     * @return self
     */
    public function setCurrencySymbol($currencySymbol);
    /**
     * Total amount of taxes applied to the order.
     *
     * xsd restriction: two decimal float, min value 0.00
     * @return float
     */
    public function getTotalTaxAmount();
    /**
     * @param float
     * @return self
     */
    public function setTotalTaxAmount($totalTaxAmount);
    /**
     * Subtotal of the order.
     *
     * xsd restriction: two decimal float, min value 0.00
     * @return float
     */
    public function getSubTotalAmount();
    /**
     * @param float
     * @return self
     */
    public function setSubTotalAmount($subTotalAmount);
    /**
     * Shipping amount of the order.
     *
     * xsd restriction: two decimal float, min value 0.00
     * @return float
     */
    public function getShippedAmount();
    /**
     * @param float
     * @return self
     */
    public function setShippedAmount($shippedAmount);
    /**
     * Amount paid in duties.
     *
     * xsd restriction: two decimal float, min value 0.00
     * @return float
     */
    public function getDutyAmount();
    /**
     * @param float
     * @return self
     */
    public function setDutyAmount($dutyAmount);
    /**
     * Amount paid in fees.
     *
     * xsd restriction: two decimal float, min value 0.00
     * @return float
     */
    public function getFeesAmount();
    /**
     * @param float
     * @return self
     */
    public function setFeesAmount($feesAmount);
    /**
     * Amount in discounts.
     *
     * xsd restriction: two decimal float, min value 0.00
     * @return float
     */
    public function getDiscountAmount();
    /**
     * @param float
     * @return self
     */
    public function setDiscountAmount($discountAmount);

    /**
     * Items that have been shipped.
     *
     * @return IShippedItemIterable
     */
    public function getItems();
    /**
     * @param IOrderItemIterable
     * @return self
     */
    public function setItems(IOrderItemIterable $items);
    /**
     * Get the destination the items were shipped to. May be a customer
     * mailing address or store front address if shipped to a store.
     *
     * @return IDestination
     */
    public function getShippingDestination();
    /**
     * @param IDestination
     * @return self
     */
    public function setShippingDestination(IDestination $shippingDestination);
    /**
     * Payment methods applied to the order.
     *
     * @return IPaymentIterable
     */
    public function getPayments();
    /**
     * @param IPaymentIterable
     * @return self
     */
    public function setPayments(IPaymentIterable $payments);
    /**
     * Taxes, duties and feeds applied to the order.
     *
     * @return ITaxDescriptionIterable
     */
    public function getTaxDescriptions();
    /**
     * @param ITaxDescriptionIterable
     * @return self
     */
    public function setTaxDescriptions(ITaxDescriptionIterable $taxes);
}
