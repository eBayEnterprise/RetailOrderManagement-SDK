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

use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderPriceAdjustment implements IOrderPriceAdjustment
{
    use TTopLevelPayload, TAmount, TCurrency, TCustomer, TOrderEvent, TSummaryAmounts, TShippedAmounts;

    /** @var IPerformedAdjustmentIterable */
    protected $performedAdjustments;

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
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory();

        $this->orderItems =
            $this->buildPayloadForInterface(static::ORDER_ITEM_ITERABLE_INTERFACE);
        $this->performedAdjustments =
            $this->buildPayloadForInterface(static::ADJUSTMENT_ITERABLE_INTERFACE);

        $this->extractionPaths = [
            'currencyCode' => 'string(x:OrderSummary/@currency)',
            'currencySymbol' => 'string(x:OrderSummary/@currencySymbol)',
            'customerFirstName' => 'string(x:Customer/x:Name/x:FirstName)',
            'customerLastName' => 'string(x:Customer/x:Name/x:LastName)',
            'storeId' => 'string(@storeId)',
            'orderId' => 'string(@customerOrderId)',
            'totalAmount' => 'number(x:OrderSummary/@totalAmount)',
            'taxAmount' => 'number(x:OrderSummary/@totalTaxAmount)',
            'subtotalAmount' => 'number(x:OrderSummary/@subTotalAmount)',
            'dutyAmount' => 'number(x:OrderSummary/@dutyAmount)',
            'feesAmount' => 'number(x:OrderSummary/@feesAmount)',
            'discountAmount' => 'number(x:OrderSummary/@discountAmount)',
            'shippedAmount' => 'number(x:OrderSummary/@shippedAmount)',
        ];
        $this->optionalExtractionPaths = [
            'customerId' => 'x:Customer/@customerId',
            'customerMiddleName' => 'x:Customer/x:Name/x:MiddleName',
            'customerHonorificName' => 'x:Customer/x:Name/x:Honorific',
            'customerEmailAddress' => 'x:Customer/x:EmailAddress',
        ];
        $this->subpayloadExtractionPaths = [
            'orderItems' => 'x:AdjustedOrderItems',
            'performedAdjustments' => 'x:PerformedAdjustments',
        ];
    }

    public function getEventType()
    {
        return static::ROOT_NODE;
    }

    /**
     * Get all adjustments performed.
     * @return IPerformedAdjustmentIterable
     */
    public function getPerformedAdjustments()
    {
        return $this->performedAdjustments;
    }

    /**
     * @param IPerformedAdjustmentIterable
     * @return self
     */
    public function setPerformedAdjustments(IPerformedAdjustmentIterable $adjustments)
    {
        $this->performedAdjustments = $adjustments;
        return $this;
    }

    public function getOrderItems()
    {
        return $this->orderItems;
    }

    public function setOrderItems(IAdjustedOrderItemIterable $orderItems)
    {
        $this->orderItems = $orderItems;
        return $this;
    }

    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'customerOrderId' => $this->getCustomerOrderId(),
            'storeId' => $this->getStoreId(),
        ];
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return self::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return $this->serializeCustomer()
            . $this->getOrderItems()->serialize()
            . $this->getPerformedAdjustments()->serialize()
            . $this->serializeOrderSummary();
    }

    protected function serializeOrderSummary()
    {
        $format = '<OrderSummary totalAmount="%.2F" currency="%s" currencySymbol="%s"'
            . ' totalTaxAmount="%.2F" subTotalAmount="%.2F" shippedAmount="%.2F"'
            . ' dutyAmount="%.2F" feesAmount="%.2F" discountAmount="%.2F" />';
        return sprintf(
            $format,
            $this->formatAmount($this->getTotalAmount()),
            $this->getCurrencyCode(),
            $this->getCurrencySymbol(),
            $this->formatAmount($this->getTaxAmount()),
            $this->formatAmount($this->getSubtotalAmount()),
            $this->formatAmount($this->getShippedAmount()),
            $this->formatAmount($this->getDutyAmount()),
            $this->formatAmount($this->getFeesAmount()),
            $this->formatAmount($this->getDiscountAmount())
        );
    }
}
