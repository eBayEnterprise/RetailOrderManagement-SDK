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
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderAccepted implements IOrderAccepted
{
    use TTopLevelPayload, TOrderEvent, TLoyaltyProgramCustomer, TCurrency, TOrderItemContainer,
        TPaymentContainer, TSummaryAmounts, TAmount;

    /** @var float */
    protected $vatTaxAmount;
    /** @var string */
    protected $orderAcceptedSource;

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

        $this->loyaltyPrograms =
            $this->buildPayloadForInterface(static::LOYALTY_PROGRAM_ITERABLE_INTERFACE);
        $this->orderItems =
            $this->buildPayloadForInterface(static::ORDER_ITEM_ITERABLE_INTERFACE);
        $this->payments =
            $this->buildPayloadForInterface(static::PAYMENT_ITERABLE_INTERFACE);

        $this->extractionPaths = [
            'customerFirstName' => 'string(x:Customer/x:Name/x:FirstName)',
            'customerLastName' => 'string(x:Customer/x:Name/x:LastName)',
            'storeId' => 'string(@storeId)',
            'orderId' => 'string(@customerOrderId)',
            'orderAcceptedSource' => 'string(@orderAcceptedSource)',
            'currencyCode' => 'string(@currency)',
            'currencySymbol' => 'string(@currencySymbol)',
            'totalAmount' => 'number(x:OrderSummary/@totalAmount)',
            'taxAmount' => 'number(x:OrderSummary/@salesTaxAmount)',
            'vatTaxAmount' => 'number(x:OrderSummary/@vatTaxAmount)',
            'subtotalAmount' => 'number(x:OrderSummary/@subTotalAmount)',
            'dutyAmount' => 'number(x:OrderSummary/@dutyAmount)',
            'feesAmount' => 'number(x:OrderSummary/@feesAmount)',
            'discountAmount' => 'number(x:OrderSummary/@discountAmount)',
        ];
        $this->optionalExtractionPaths = [
            'customerId' => 'x:Customer/@customerId',
            'customerMiddleName' => 'x:Customer/x:Name/x:MiddleName',
            'customerHonorificName' => 'x:Customer/x:Name/x:Honorific',
            'customerEmailAddress' => 'x:Customer/x:EmailAddress',
        ];
        $this->subpayloadExtractionPaths = [
            'loyaltyPrograms' => 'x:Customer/x:LoyaltyPrograms',
            'orderItems' => 'x:OrderAcceptedOrderItems',
            'payments' => 'x:OrderAcceptedPayments',
        ];
    }

    public function getVatTaxAmount()
    {
        return $this->vatTaxAmount;
    }

    public function setVatTaxAmount($vatTaxAmount)
    {
        $this->vatTaxAmount = $this->sanitizeAmount($vatTaxAmount);
        return $this;
    }

    public function getOrderAcceptedSource()
    {
        return $this->orderAcceptedSource;
    }

    public function setOrderAcceptedSource($orderAcceptedSource)
    {
        $this->orderAcceptedSource = $orderAcceptedSource;
        return $this;
    }

    public function getEventType()
    {
        return static::ROOT_NODE;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    protected function serializeContents()
    {
        return $this->serializeCustomer()
            . $this->getOrderItems()->serialize()
            . $this->getPayments()->serialize()
            . $this->serializeOrderSummary();
    }

    protected function serializeOrderSummary()
    {
        $format = '<OrderSummary totalAmount="%s" salesTaxAmount="%s" '
            . 'vatTaxAmount="%s" subTotalAmount="%s" dutyAmount="%s" '
            . 'feesAmount="%s" discountAmount="%s"/>';
        return sprintf(
            $format,
            $this->formatAmount($this->getTotalAmount()),
            $this->formatAmount($this->getTaxAmount()),
            $this->formatAmount($this->getVatTaxAmount()),
            $this->formatAmount($this->getSubtotalAmount()),
            $this->formatAmount($this->getDutyAmount()),
            $this->formatAmount($this->getFeesAmount()),
            $this->formatAmount($this->getDiscountAmount())
        );
    }

    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'storeId' => $this->getStoreId(),
            'customerOrderId' => $this->getCustomerOrderId(),
            'currency' => $this->getCurrencyCode(),
            'currencySymbol' => $this->getCurrencySymbol(),
            'orderAcceptedSource' => $this->getOrderAcceptedSource(),
        ];
    }
}
