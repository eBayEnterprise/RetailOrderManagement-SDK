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

use eBayEnterprise\RetailOrderManagement\Payload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderConfirmed implements IOrderConfirmed
{
    use Payload\TTopLevelPayload, Payload\Payment\TAmount, TOrderEvent, TCurrency, TLoyaltyProgramCustomer, TPaymentContainer, TSummaryAmounts;

    /** @var float */
    protected $shippingAmount;
    /** @var IOrderConfirmedShipGroupIterable */
    protected $shipGroups;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        Payload\IValidatorIterator $validators,
        Payload\ISchemaValidator $schemaValidator,
        Payload\IPayloadMap $payloadMap,
        LoggerInterface $logger,
        Payload\IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new Payload\PayloadFactory();

        $this->loyaltyPrograms =
            $this->buildPayloadForInterface(static::LOYALTY_PROGRAM_ITERABLE_INTERFACE);
        $this->shipGroups =
            $this->buildPayloadForInterface(static::SHIP_GROUP_ITERABLE_INTERFACE);
        $this->payments =
            $this->buildPayloadForInterface(static::PAYMENT_ITERABLE_INTERFACE);

        $this->extractionPaths = [
            'storeId' => 'string(./@storeId)',
            'currencyCode' => 'string(./@currency)',
            'currencySymbol' => 'string(./@currencySymbol)',
            'customerLastName' => 'string(x:Customer/x:Name/x:LastName)',
            'customerFirstName' => 'string(x:Customer/x:Name/x:FirstName)',
            // order summary amounts
            'totalAmount' => 'string(x:OrderSummary/@totalAmount)',
            'taxAmount' => 'string(x:OrderSummary/@salesTaxAmount)',
            'subtotalAmount' => 'string(x:OrderSummary/@subTotalAmount)',
            'shippingAmount' => 'string(x:OrderSummary/@shippingAmount)',
            'dutyAmount' => 'string(x:OrderSummary/@dutyAmount)',
            'feesAmount' => 'string(x:OrderSummary/@feesAmount)',
            'discountAmount' => 'string(x:OrderSummary/@discountAmount)',
        ];
        $this->optionalExtractionPaths = [
            'orderId' => './@customerOrderId',
            'customerId' => 'x:Customer/@customerId',
            'customerMiddleName' => 'x:Customer/x:Name/x:MiddleName',
            'customerHonorificName' => 'x:Customer/x:Name/x:Honorific',
            'customerEmailAddress' => 'x:Customer/x:EmailAddress',
        ];
        $this->subpayloadExtractionPaths = [
            'loyaltyPrograms' => 'x:Customer/x:LoyaltyPrograms',
            'shipGroups' => 'x:ShipGroups',
            'payments' => 'x:OrderConfirmedPayments',
        ];
    }

    /**
     * Get the event type
     * @return string
     */
    public function getEventType()
    {
        return $this->getRootNodeName();
    }

    public function getShipGroups()
    {
        return $this->shipGroups;
    }

    public function setShipGroups(IShipGroupIterable $shipGroups)
    {
        $this->shipGroups = $shipGroups;
        return $this;
    }

    /**
     * Get the total shipping amount
     * @return float
     */
    public function getShippingAmount()
    {
        return $this->shippingAmount;
    }

    /**
     * @param  float $amount
     * @return self
     */
    public function setShippingAmount($amount)
    {
        $this->shippingAmount = $amount;
        return $this;
    }

    /**
     * Serialize the contained data to an xml string
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeCustomer() .
            $this->shipGroups->serialize() .
            $this->payments->serialize() .
            $this->serializeSummaryAmounts();
    }

    protected function serializeSummaryAmounts()
    {
        $attrs = [
            'totalAmount' => $this->getTotalAmount(),
            'salesTaxAmount' => $this->getTaxAmount(),
            'subTotalAmount' => $this->getSubtotalAmount(),
            'shippingAmount' => $this->getShippingAmount(),
            'dutyAmount' => $this->getDutyAmount(),
            'feesAmount' => $this->getFeesAmount(),
            'discountAmount' => $this->getDiscountAmount(),
        ];
        $qualifyAttributes = function ($name) use ($attrs) {
            return sprintf('%s="%s"', $name, $this->formatAmount($attrs[$name] ?: 0));
        };
        $qualifiedAttributes = array_map($qualifyAttributes, array_keys($attrs));
        return '<OrderSummary ' . implode(' ', $qualifiedAttributes) . '/>';
    }

    protected function getSchemaFile()
    {
        return __DIR__ . '/../../Schemas/events/1.0/events/' . self::XSD;
    }

    protected function getRootNodeName()
    {
        return self::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    /**
     * Name, value pairs of root attributes
     * @return array
     */
    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'customerOrderId' => $this->getCustomerOrderId(),
            'storeId' => $this->getStoreId(),
            'currency' => $this->getCurrencyCode(),
            'currencySymbol' => $this->getCurrencySymbol(),
        ];
    }
}
