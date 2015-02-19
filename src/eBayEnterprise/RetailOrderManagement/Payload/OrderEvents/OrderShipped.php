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

class OrderShipped implements IOrderShipped
{
    use TTopLevelPayload, TAmount, TCurrency, TLoyaltyProgramCustomer, TOrderEvent, TOrderItemContainer,
        TPaymentContainer, TSummaryAmounts, TShippedAmounts, TTaxDescriptionContainer;

    /** @var IDestination */
    protected $destination;

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
        $this->payments =
            $this->buildPayloadForInterface(static::PAYMENT_ITERABLE_INTERFACE);
        $this->taxDescriptions =
            $this->buildPayloadForInterface(static::TAX_DESCRIPTION_ITERABLE_INTERFACE);
        $this->orderItems =
            $this->buildPayloadForInterface(static::ORDER_ITEM_ITERABLE_INTERFACE);

        $this->extractionPaths = [
            'currencyCode' => 'string(@currency)',
            'currencySymbol' => 'string(@currencySymbol)',
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
            'loyaltyPrograms' => 'x:Customer/x:LoyaltyPrograms',
            'orderItems' => 'x:ShippedOrderItems',
            'payments' => 'x:OrderSummary/x:Payments',
            'taxDescriptions' => 'x:OrderSummary/x:OrderTaxesDutiesFeesInformations',
        ];
    }

    public function getEventType()
    {
        return static::ROOT_NODE;
    }

    public function getShippingDestination()
    {
        return $this->destination;
    }

    public function setShippingDestination(IDestination $destination)
    {
        $this->destination = $destination;
        return $this;
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        return $this->deserializeShippingDestination($xpath);
    }

    protected function deserializeShippingDestination(DOMXPath $xpath)
    {
        $destinationNode = $xpath->query('x:ShippedDestination/*')->item(0);
        $mailingAddress = static::MAILING_ADDRESS_INTERFACE;
        $storeFront = static::STORE_FRONT_DETAILS_INTERFACE;
        $destination = null;
        switch ($destinationNode->nodeName) {
            case $mailingAddress::ROOT_NODE:
                $destination = $this->buildPayloadForInterface($mailingAddress);
                break;
            case $storeFront::ROOT_NODE:
                $destination = $this->buildPayloadForInterface($storeFront);
                break;
        }
        if ($destination) {
            $destination->deserialize($destinationNode->C14N());
            $this->setShippingDestination($destination);
        }
        return $this;
    }

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
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return $this->serializeCustomer()
            . $this->getOrderItems()->serialize()
            . "<ShippedDestination>{$this->getShippingDestination()->serialize()}</ShippedDestination>"
            . $this->serializeOrderSummary();
    }

    protected function serializeOrderSummary()
    {
        $format = '<OrderSummary totalAmount="%s"'
            . ' totalTaxAmount="%s" subTotalAmount="%s" shippedAmount="%s"'
            . ' dutyAmount="%s" feesAmount="%s" discountAmount="%s">'
            . '%s%s</OrderSummary>';
        return sprintf(
            $format,
            $this->formatAmount($this->getTotalAmount()),
            $this->formatAmount($this->getTaxAmount()),
            $this->formatAmount($this->getSubtotalAmount()),
            $this->formatAmount($this->getShippedAmount()),
            $this->formatAmount($this->getDutyAmount()),
            $this->formatAmount($this->getFeesAmount()),
            $this->formatAmount($this->getDiscountAmount()),
            $this->getPayments()->serialize(),
            $this->getTaxDescriptions()->serialize()
        );
    }
}
