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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use BadMethodCallException;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use eBayEnterprise\RetailOrderManagement\Payload\Order\PaymentIterable;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

class OrderDetailPaymentIterable extends PaymentIterable
{
    const ORDER_DETAIL_CREDIT_CARD_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IOrderDetailCreditCardPayment';
    const ORDER_DETAIL_STORED_VALUE_CARD_PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IOrderDetailStoredValueCardPayment';

    /**
     * Get a new, empty credit card payment object.
     *
     * @return IOrderDetailCreditCardPayment
     */
    public function getEmptyCreditCardPayment()
    {
        return $this->buildPayloadForInterface(static::ORDER_DETAIL_CREDIT_CARD_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty stored value card payment object.
     *
     * @return IOrderDetailStoredValueCardPayment
     */
    public function getEmptyStoredValueCardPayment()
    {
        return $this->buildPayloadForInterface(static::ORDER_DETAIL_STORED_VALUE_CARD_PAYMENT_INTERFACE);
    }
}
