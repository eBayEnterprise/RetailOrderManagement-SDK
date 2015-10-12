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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Order\TOrderItemContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\TFeeContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\TItemRelationshipContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\TCustomAttributeContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\TTemplateContainer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use DateTime;

class OrderResponse implements IOrderResponse
{
    use TPayload, TOrderDetailItemContainer, TFeeContainer, TItemRelationshipContainer, TShipmentContainer, TCustomAttributeContainer, TTemplateContainer, TExchangeOrderContainer, TChargeGroupContainer;

    /** @var string */
    protected $customerOrderId;
    /** @var string */
    protected $levelOfService;
    /** @var bool */
    protected $hasChainedLines;
    /** @var bool */
    protected $hasDerivedChild;
    /** @var string */
    protected $sourceId;
    /** @var string */
    protected $sourceIdType;
    /** @var IOrderDetailCustomer */
    protected $customer;
    /** @var DateTime */
    protected $createTime;
    /** @var IOrderDetailShipping */
    protected $shipping;
    /** @var IOrderDetailPayment */
    protected $payment;
    /** @var string */
    protected $shopRunnerMessage;
    /** @var string */
    protected $currency;
    /** @var IAssociate */
    protected $associate;
    /** @var ITaxHeader */
    protected $taxHeader;
    /** @var string */
    protected $printedCatalogCode;
    /** @var string */
    protected $locale;
    /** @var string */
    protected $dashboardRepId;
    /** @var string */
    protected $orderSource;
    /** @var string */
    protected $orderSourceType;
    /** @var string */
    protected $status;
    /** @var string */
    protected $orderHistoryUrl;
    /** @var bool */
    protected $vatInclusivePricing;

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
        $this->parentPayload = $parentPayload;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = $this->getNewPayloadFactory();

        $this->initExtractPaths()
            ->initOptionalExtractPaths()
            ->initDatetimeExtractPaths()
            ->initSubPayloadExtractPaths()
            ->initSubPayloadProperties();
    }

    /**
     * Initialize the protected class property array self::extractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initExtractPaths()
    {
        $this->extractionPaths = [
            'currency' => 'string(x:Currency)',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::optionalExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initOptionalExtractPaths()
    {
        $this->optionalExtractionPaths = [
            'customerOrderId' => '@customerOrderId',
            'levelOfService' => '@levelOfService',
            'hasChainedLines' => '@hasChainedLines',
            'hasDerivedChild' => '@hasDerivedChild',
            'sourceId' => 'x:SourceId',
            'sourceIdType' => 'x:SourceId/@type',
            'shopRunnerMessage' => 'x:ShopRunnerMessage',
            'printedCatalogCode' => 'x:PrintedCatalogCode',
            'locale' => 'x:Locale',
            'dashboardRepId' => 'x:DashboardRepId',
            'orderSource' => 'x:OrderSource',
            'orderSourceType' => 'x:OrderSource/@type',
            'status' => 'x:Status',
            'orderHistoryUrl' => 'x:OrderHistoryUrl',
            'vatInclusivePricing' => 'x:VATInclusivePricing',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::subpayloadExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initSubPayloadExtractPaths()
    {
        $this->subpayloadExtractionPaths = [
            'customer' => 'x:Customer',
            'orderItems' => 'x:OrderItems',
            'shipping' => 'x:Shipping',
            'payment' => 'x:Payment',
            'fees' => 'x:Fees',
            'associate' => 'x:Associate',
            'taxHeader' => 'x:TaxHeader',
            'itemRelationships' => 'x:Relationships',
            'shipments' => 'x:Shipments',
            'customAttributes' => 'x:CustomAttributes',
            'templates' => 'x:Templates',
            'exchangeOrders' => 'x:ExchangeOrders',
            'chargeGroups' => 'x:ChargeGroups',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::datetimeExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initDatetimeExtractPaths()
    {
        $this->datetimeExtractionPaths = [
            'createTime' => 'string(x:CreateTime)',
        ];
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setCustomer($this->buildPayloadForInterface(
            static::CUSTOMER_INTERFACE
        ));
        $this->setOrderDetailItems($this->buildPayloadForInterface(
            static::ORDER_ITEM_ITERABLE_INTERFACE
        ));
        $this->setShipping($this->buildPayloadForInterface(
            static::SHIPPING_INTERFACE
        ));
        $this->setPayment($this->buildPayloadForInterface(
            static::PAYMENT_INTERFACE
        ));
        $this->setFees($this->buildPayloadForInterface(
            static::FEE_ITERABLE_INTERFACE
        ));
        $this->setAssociate($this->buildPayloadForInterface(
            static::ASSOCIATE_INTERFACE
        ));
        $this->setTaxHeader($this->buildPayloadForInterface(
            static::TAX_HEADER_INTERFACE
        ));
        $this->setItemRelationships($this->buildPayloadForInterface(
            static::ITEM_RELATIONSHIP_ITERABLE_INTERFACE
        ));
        $this->setShipments($this->buildPayloadForInterface(
            static::SHIPMENT_ITERABLE_INTERFACE
        ));
        $this->setCustomAttributes($this->buildPayloadForInterface(
            static::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE
        ));
        $this->setTemplates($this->buildPayloadForInterface(
            static::TEMPLATE_ITERABLE_INTERFACE
        ));
        $this->setExchangeOrders($this->buildPayloadForInterface(
            static::EXCHANGE_ORDER_ITERABLE_INTERFACE
        ));
        $this->setChargeGroups($this->buildPayloadForInterface(
            self::CHARGE_GROUP_ITERABLE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IOrderResponse::getCustomerOrderId()
     */
    public function getCustomerOrderId()
    {
        return $this->customerOrderId;
    }

    /**
     * @see IOrderResponse::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setCustomerOrderId($customerOrderId)
    {
        $this->customerOrderId = $customerOrderId;
        return $this;
    }

    /**
     * @see IOrderResponse::getLevelOfService()
     */
    public function getLevelOfService()
    {
        return $this->levelOfService;
    }

    /**
     * @see IOrderResponse::setLevelOfService()
     * @codeCoverageIgnore
     */
    public function setLevelOfService($levelOfService)
    {
        $this->levelOfService = $levelOfService;
        return $this;
    }

    /**
     * @see IOrderResponse::getHasChainedLines()
     */
    public function getHasChainedLines()
    {
        return $this->hasChainedLines;
    }

    /**
     * @see IOrderResponse::setHasChainedLines()
     * @codeCoverageIgnore
     */
    public function setHasChainedLines($hasChainedLines)
    {
        $this->hasChainedLines = $hasChainedLines;
        return $this;
    }

    /**
     * @see IOrderResponse::getHasDerivedChild()
     */
    public function getHasDerivedChild()
    {
        return $this->hasDerivedChild;
    }

    /**
     * @see IOrderResponse::setHasDerivedChild()
     * @codeCoverageIgnore
     */
    public function setHasDerivedChild($hasDerivedChild)
    {
        $this->hasDerivedChild = $hasDerivedChild;
        return $this;
    }

    /**
     * @see IOrderResponse::getSourceId()
     */
    public function getSourceId()
    {
        return $this->sourceId;
    }

    /**
     * @see IOrderResponse::setSourceId()
     * @codeCoverageIgnore
     */
    public function setSourceId($sourceId)
    {
        $this->sourceId = $sourceId;
        return $this;
    }

    /**
     * @see IOrderResponse::getSourceIdType()
     */
    public function getSourceIdType()
    {
        return $this->sourceIdType;
    }

    /**
     * @see IOrderResponse::setSourceIdType()
     * @codeCoverageIgnore
     */
    public function setSourceIdType($sourceIdType)
    {
        $this->sourceIdType = $sourceIdType;
        return $this;
    }

    /**
     * @see IOrderResponse::getCustomer()
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @see IOrderResponse::setCustomer()
     * @codeCoverageIgnore
     */
    public function setCustomer(IOrderDetailCustomer $customer)
    {
        $this->customer = $customer;
        return $this;
    }

    /**
     * @see IOrderResponse::getCreateTime()
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * @see IOrderResponse::setCreateTime()
     * @codeCoverageIgnore
     */
    public function setCreateTime(DateTime $createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * @see IOrderResponse::getShipping()
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * @see IOrderResponse::setShipping()
     * @codeCoverageIgnore
     */
    public function setShipping(IOrderDetailShipping $shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    /**
     * @see IOrderResponse::getPayment()
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @see IOrderResponse::setPayment()
     * @codeCoverageIgnore
     */
    public function setPayment(IOrderDetailPayment $payment)
    {
        $this->payment = $payment;
        return $this;
    }

    /**
     * @see IOrderResponse::getShopRunnerMessage()
     */
    public function getShopRunnerMessage()
    {
        return $this->shopRunnerMessage;
    }

    /**
     * @see IOrderResponse::setShopRunnerMessage()
     * @codeCoverageIgnore
     */
    public function setShopRunnerMessage($shopRunnerMessage)
    {
        $this->shopRunnerMessage = $shopRunnerMessage;
        return $this;
    }

    /**
     * @see IOrderResponse::getCurrency()
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @see IOrderResponse::setCurrency()
     * @codeCoverageIgnore
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @see IOrderResponse::getAssociate()
     */
    public function getAssociate()
    {
        return $this->associate;
    }

    /**
     * @see IOrderResponse::setAssociate()
     * @codeCoverageIgnore
     */
    public function setAssociate(IAssociate $associate)
    {
        $this->associate = $associate;
        return $this;
    }

    /**
     * @see IOrderResponse::getTaxHeader()
     */
    public function getTaxHeader()
    {
        return $this->taxHeader;
    }

    /**
     * @see IOrderResponse::setTaxHeader()
     * @codeCoverageIgnore
     */
    public function setTaxHeader(ITaxHeader $taxHeader)
    {
        $this->taxHeader = $taxHeader;
        return $this;
    }

    /**
     * @see IOrderResponse::getPrintedCatalogCode()
     */
    public function getPrintedCatalogCode()
    {
        return $this->printedCatalogCode;
    }

    /**
     * @see IOrderResponse::setPrintedCatalogCode()
     * @codeCoverageIgnore
     */
    public function setPrintedCatalogCode($printedCatalogCode)
    {
        $this->printedCatalogCode = $printedCatalogCode;
        return $this;
    }

    /**
     * @see IOrderResponse::getLocale()
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @see IOrderResponse::setLocale()
     * @codeCoverageIgnore
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    /**
     * @see IOrderResponse::getDashboardRepId()
     */
    public function getDashboardRepId()
    {
        return $this->dashboardRepId;
    }

    /**
     * @see IOrderResponse::setDashboardRepId()
     * @codeCoverageIgnore
     */
    public function setDashboardRepId($dashboardRepId)
    {
        $this->dashboardRepId = $dashboardRepId;
        return $this;
    }

    /**
     * @see IOrderResponse::getOrderSource()
     */
    public function getOrderSource()
    {
        return $this->orderSource;
    }

    /**
     * @see IOrderResponse::setOrderSource()
     * @codeCoverageIgnore
     */
    public function setOrderSource($orderSource)
    {
        $this->orderSource = $orderSource;
        return $this;
    }

    /**
     * @see IOrderResponse::getOrderSourceType()
     */
    public function getOrderSourceType()
    {
        return $this->orderSourceType;
    }

    /**
     * @see IOrderResponse::setOrderSourceType()
     * @codeCoverageIgnore
     */
    public function setOrderSourceType($orderSourceType)
    {
        $this->orderSourceType = $orderSourceType;
        return $this;
    }

    /**
     * @see IOrderResponse::getStatus()
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @see IOrderResponse::setStatus()
     * @codeCoverageIgnore
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @see IOrderResponse::getOrderHistoryUrl()
     */
    public function getOrderHistoryUrl()
    {
        return $this->orderHistoryUrl;
    }

    /**
     * @see IOrderResponse::setOrderHistoryUrl()
     * @codeCoverageIgnore
     */
    public function setOrderHistoryUrl($orderHistoryUrl)
    {
        $this->orderHistoryUrl = $orderHistoryUrl;
        return $this;
    }

    /**
     * @see IOrderResponse::getVatInclusivePricing()
     */
    public function getVatInclusivePricing()
    {
        return $this->vatInclusivePricing;
    }

    /**
     * @see IOrderResponse::setVatInclusivePricing()
     * @codeCoverageIgnore
     */
    public function setVatInclusivePricing($vatInclusivePricing)
    {
        $this->vatInclusivePricing = $vatInclusivePricing;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeSourceIdValue('SourceId', $this->getSourceId())
            . $this->getCustomer()->serialize()
            . $this->serializeOptionalDateValue('CreateTime', 'c', $this->getCreateTime())
            . $this->getOrderDetailItems()->serialize()
            . $this->getShipping()->serialize()
            . $this->getPayment()->serialize()
            . $this->getChargeGroups()->serialize()
            . $this->getFees()->serialize()
            . $this->serializeOptionalXmlEncodedValue('ShopRunnerMessage', $this->getShopRunnerMessage())
            . $this->serializeRequiredValue('Currency', $this->xmlEncode($this->getCurrency()))
            . $this->getAssociate()->serialize()
            . $this->getTaxHeader()->serialize()
            . $this->serializeOptionalXmlEncodedValue('PrintedCatalogCode', $this->getPrintedCatalogCode())
            . $this->serializeOptionalXmlEncodedValue('Locale', $this->getLocale())
            . $this->getItemRelationships()->serialize()
            . $this->getShipments()->serialize()
            . $this->serializeOptionalXmlEncodedValue('DashboardRepId', $this->getDashboardRepId())
            . $this->serializeOrderSourceValue('OrderSource', $this->getOrderSource())
            . $this->serializeOptionalXmlEncodedValue('Status', $this->getStatus())
            . $this->getCustomAttributes()->serialize()
            . $this->getTemplates()->serialize()
            . $this->serializeOrderHistoryUrlValue('OrderHistoryUrl', $this->getOrderHistoryUrl())
            . $this->serializeOptionalXmlEncodedValue('VATInclusivePricing', $this->getVatInclusivePricing())
            . $this->getExchangeOrders()->serialize();
    }

    /**
     * Serialize order history URL XML node.
     *
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeOrderHistoryUrlValue($nodeName, $value)
    {
        $orderHistoryUrl = $this->getOrderHistoryUrl();
        return $orderHistoryUrl
            ? $this->serializeOptionalXmlEncodedValue('OrderHistoryUrl', $orderHistoryUrl) : null;
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $customerOrderId = $this->getCustomerOrderId();
        $levelOfService = $this->getLevelOfService();
        $hasChainedLines = $this->getHasChainedLines();
        $hasDerivedChild = $this->getHasDerivedChild();
        return array_merge(
            $customerOrderId ? ['customerOrderId' => $customerOrderId] : [],
            $levelOfService ? ['levelOfService' => $levelOfService] : [],
            !empty($hasChainedLines) ? ['hasChainedLines' => $hasChainedLines] : [],
            !empty($hasDerivedChild) ? ['hasDerivedChild' => $hasDerivedChild] : []
        );
    }

    /**
     * Serialize the OrderSource XML node.
     *
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeOrderSourceValue($nodeName, $value)
    {
        $typeAttribute = $this->serializeOptionalAttribute('type', $this->xmlEncode($this->getOrderSourceType()));
        return $value
            ? sprintf('<%s %s>%s</%1$s>', $nodeName, $typeAttribute, $this->xmlEncode($value)) : null;
    }

    /**
     * Serialize the SourceId XML node.
     *
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeSourceIdValue($nodeName, $value)
    {
        $typeAttribute = $this->serializeOptionalAttribute('type', $this->xmlEncode($this->getSourceIdType()));
        return $value
            ? sprintf('<%s %s>%s</%1$s>', $nodeName, $typeAttribute, $this->xmlEncode($value)) : null;
    }
}
