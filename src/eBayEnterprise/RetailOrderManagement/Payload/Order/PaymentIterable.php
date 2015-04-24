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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use BadMethodCallException;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PaymentIterable extends SPLObjectStorage implements IPaymentIterable
{
    use TIterablePayload;

    const ROOT_NODE = 'Payments';

    /** @var array Mapping of payment node names and the method to get a new instance of its associated payload */
    protected $subpayloadNodeMap = [
        'CreditCard' => 'getEmptyCreditCardPayment',
        'PrepaidCreditCard' => 'getEmptyPrepaidCreditCardPayment',
        'Points' => 'getEmptyPointsPayment',
        'StoredValueCard' => 'getEmptyStoredValueCardPayment',
        'PayPal' => 'getEmptyPayPalPayment',
        'PrepaidCashOnDelivery' => 'getEmptyPrepaidCashOnDeliveryPayment',
        'ReservationPayment' => 'getEmptyReservationPayment',
    ];

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;
    }

    public function deserialize($serializedData)
    {
        $xpath = $this->getPayloadAsXPath($serializedData);
        foreach ($xpath->query($this->getSubpayloadXPath()) as $subpayloadNode) {
            if (isset($this->subpayloadNodeMap[$subpayloadNode->nodeName])) {
                $plFactoryMethod = $this->subpayloadNodeMap[$subpayloadNode->nodeName];
                $pl = $this->$plFactoryMethod();
            }
            if (isset($pl)) {
                $pl->deserialize($subpayloadNode->C14N());
                $this->offsetSet($pl);
            }
        }
        $this->validate();
        return $this;
    }

    public function getNewSubpayload()
    {
        throw new BadMethodCallException('Method not supported for this type.');
    }

    /**
     * Get a new, empty credit card payment object.
     *
     * @return ICreditCardPayment
     */
    public function getEmptyCreditCardPayment()
    {
        return $this->buildPayloadForInterface(static::CREDIT_CARD_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty prepaid credit card payment object.
     *
     * @return IPrepaidCreditCardPayment
     */
    public function getEmptyPrepaidCreditCardPayment()
    {
        return $this->buildPayloadForInterface(static::PREPAID_CREDIT_CARD_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty points payment object.
     *
     * @return IPointsPayment
     */
    public function getEmptyPointsPayment()
    {
        return $this->buildPayloadForInterface(static::POINTS_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty stored value card payment object.
     *
     * @return IStoredValueCardPayment
     */
    public function getEmptyStoredValueCardPayment()
    {
        return $this->buildPayloadForInterface(static::STORED_VALUE_CARD_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty pay pal payment object.
     *
     * @return IPayPalPayment
     */
    public function getEmptyPayPalPayment()
    {
        return $this->buildPayloadForInterface(static::PAY_PAL_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty prepaid cash on delivery payment object.
     *
     * @return IPrepaidCashOnDeliveryPayment
     */
    public function getEmptyPrepaidCashOnDeliveryPayment()
    {
        return $this->buildPayloadForInterface(static::PREPAID_CASH_ON_DELIVERY_PAYMENT_INTERFACE);
    }

    /**
     * Get a new, empty reservation payment payment object.
     *
     * @return IReservationPayment
     */
    public function getEmptyReservationPayment()
    {
        return $this->buildPayloadForInterface(static::RESERVATION_PAYMENT_INTERFACE);
    }

    /**
     * Derive the subpayload XPath from the mapping of supported subpayload
     * node names.
     *
     * @return string
     */
    public function getSubpayloadXPath()
    {
        return 'x:' . implode('|x:', array_keys($this->subpayloadNodeMap));
    }

    public function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    public function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
