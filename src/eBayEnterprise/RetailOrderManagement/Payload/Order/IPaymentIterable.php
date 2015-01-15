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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IPaymentIterable extends \Countable, \Iterator, \ArrayAccess, IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const CREDIT_CARD_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICreditCardPayment';
    const PREPAID_CREDIT_CARD_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPrepaidCreditCardPayment';
    const POINTS_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPointsPayment';
    const STORED_VALUE_CARD_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IStoredValueCardPayment';
    const PAY_PAL_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPayPalPayment';
    const PREPAID_CASH_ON_DELIVERY_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPrepaidCashOnDeliveryPayment';
    const RESERVATION_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IReservationPayment';

    /**
     * Get a new, empty credit card payment object.
     *
     * @return ICreditCardPayment
     */
    public function getEmptyCreditCardPayment();

    /**
     * Get a new, empty prepaid credit card payment object.
     *
     * @return IPrepaidCreditCardPayment
     */
    public function getEmptyPrepaidCreditCardPayment();

    /**
     * Get a new, empty points payment object.
     *
     * @return IPointsPayment
     */
    public function getEmptyPointsPayment();

    /**
     * Get a new, empty stored value card payment object.
     *
     * @return IStoredValueCardPayment
     */
    public function getEmptyStoredValueCardPayment();

    /**
     * Get a new, empty pay pal payment object.
     *
     * @return IPayPalPayment
     */
    public function getEmptyPayPalPayment();

    /**
     * Get a new, empty prepaid cash on delivery payment object.
     *
     * @return IPrepaidCashOnDeliveryPayment
     */
    public function getEmptyPrepaidCashOnDeliveryPayment();

    /**
     * Get a new, empty reservation payment payment object.
     *
     * @return IReservationPayment
     */
    public function getEmptyReservationPayment();
}
