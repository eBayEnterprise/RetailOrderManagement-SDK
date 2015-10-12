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

use DateTime;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TDestinationContainer;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderCreateRequest implements IOrderCreateRequest
{
    use TTopLevelPayload, TOrderCustomer, TOrderItemContainer, TShipGroupContainer,
        TDestinationContainer, TPaymentContainer, TItemRelationshipContainer, TOrderHoldContainer,
        TCustomAttributeContainer, TTemplateContainer, TOrderContext, TAmount;

    /** @var string */
    protected $orderType;
    /** @var string */
    protected $requestId;
    /** @var string */
    protected $testType;
    /** @var string */
    protected $orderId;
    /** @var string */
    protected $levelOfService;
    /** @var DateTime */
    protected $createTime;
    /** @var IMailingAddress */
    protected $billingAddress;
    /** @var string */
    protected $billingAddressIdRef;
    /** @var string */
    protected $shopRunnerMessage;
    /** @var string */
    protected $currency;
    /** @var string */
    protected $associateName;
    /** @var string */
    protected $associateNumber;
    /** @var string */
    protected $associateStore;
    /** @var bool */
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
    protected $orderHistoryUrl;
    /** @var bool */
    protected $vatInclusivePricing;
    /** @var string */
    protected $orderTotal;

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
        $this->payloadFactory = new PayloadFactory;

        $this->setupSubPayloads();

        $this->extractionPaths = array_merge(
            [
                'requestId' => 'string(@requestId)',
                'orderId' => 'string(x:Order/@customerOrderId)',
                'billingAddressIdRef' => 'string(x:Order/x:Payment/x:BillingAddress/@ref)',
                'currency' => 'string(x:Order/x:Currency)',
                'locale' => 'string(x:Order/x:Locale)',
                'orderTotal' => 'number(x:Order/x:OrderTotal)',
                'firstName' => 'string(x:Order/x:Customer/x:Name/x:FirstName)',
                'lastName' => 'string(x:Order/x:Customer/x:Name/x:LastName)',
            ],
            $this->getOrderContextExtractionPaths()
        );
        $this->optionalExtractionPaths = array_merge(
            [
                'orderType' => '@orderType',
                'testType' => '@testType',
                'levelOfService' => 'x:Order/@levelOfService',
                'shopRunnerMessage' => 'x:Order/x:ShopRunnerMessage',
                'associateName' => 'x:Order/x:Associate/x:Name',
                'associateNumber' => 'x:Order/x:Associate/x:Number',
                'associateStore' => 'x:Order/x:Associate/x:Store',
                'printedCatalogCode' => 'x:Order/x:PrintedCatalogCode',
                'dashboardRepId' => 'x:Order/x:DashboardRepId',
                'orderSource' => 'x:Order/x:OrderSource',
                'orderSourceType' => 'x:Order/x:OrderSource/@type',
                'orderHistoryUrl' => 'x:Order/x:OrderHistoryUrl',
                'customerId' => 'x:Order/x:Customer/@customerId',
                'gender' => 'x:Order/x:Customer/x:Gender',
                'emailAddress' => 'x:Order/x:Customer/x:EmailAddress',
                'taxId' => 'x:Order/x:Customer/x:CustomerTaxId',
                'middleName' => 'x:Order/x:Customer/x:Name/x:MiddleName',
                'honorificName' => 'x:Order/x:Customer/x:Name/x:Honorific',
            ],
            $this->getOrderContextOptionalExtractionPaths()
        );
        $this->booleanExtractionPaths = [
            'taxHeader' => 'string(x:Order/x:TaxHeader/x:Error)',
            'vatInclusivePricing' => 'string(x:Order/x:VATInclusivePricing)',
            'taxExempt' => 'string(x:Order/x:Customer/x:TaxExemptFlag)',
        ];
        $this->subpayloadExtractionPaths = array_merge(
            [
                'loyaltyPrograms' => 'x:Order/x:Customer/x:LoyaltyPrograms',
                'orderItems' => 'x:Order/x:OrderItems',
                'payments' => 'x:Order/x:Payment/x:Payments',
                'shipGroups' => 'x:Order/x:Shipping/x:ShipGroups',
                'destinations' => 'x:Order/x:Shipping/x:Destinations',
                'itemRelationships' => 'x:Order/x:Relationships',
                'holds' => 'x:Order/x:Holds',
                'customAttributes' => 'x:Order/x:CustomAttributes',
                'templates' => 'x:Order/x:Templates',
            ],
            $this->getOrderContextSubpayloadExtractionPaths()
        );
    }

    public function getOrderType()
    {
        return $this->orderType;
    }

    public function setOrderType($orderType)
    {
        if ($orderType === self::ORDER_TYPE_SALES ||
            $orderType === self::ORDER_TYPE_RETURN ||
            $orderType === self::ORDER_TYPE_PURCHASE ||
            $orderType === self::ORDER_TYPE_TRANSFER
        ) {
            $this->orderType = $orderType;
        }
        return $this;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $this->cleanString($requestId, 40);
        return $this;
    }

    public function getTestType()
    {
        return $this->testType;
    }

    public function setTestType($testType)
    {
        if ($testType === self::TEST_TYPE_WEBONLY ||
            $testType === self::TEST_TYPE_AUTOCANCEL ||
            $testType === self::TEST_TYPE_NORELEASE ||
            $testType === self::TEST_TYPE_AUTOSHIP
        ) {
            $this->testType = $testType;
        }
        return $this;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $this->cleanString($orderId, 24);
        return $this;
    }

    public function getLevelOfService()
    {
        return $this->levelOfService;
    }

    public function setLevelOfService($levelOfService)
    {
        if ($levelOfService === self::LEVEL_OF_SERVICE_REGULAR ||
            $levelOfService === self::LEVEL_OF_SERVICE_RUSH
        ) {
            $this->levelOfService = $levelOfService;
        }
        return $this;
    }

    public function getCreateTime()
    {
        return $this->createTime;
    }

    public function setCreateTime(DateTime $createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    public function getBillingAddress()
    {
        if (!$this->billingAddress && $this->billingAddressIdRef) {
            foreach ($this->getDestinations() as $destination) {
                if ($destination->getId() === $this->billingAddressIdRef) {
                    $this->billingAddress = $destination;
                    break;
                }
            }
        }
        return $this->billingAddress;
    }

    public function setBillingAddress(IMailingAddress $billingAddress)
    {
        $this->billingAddress = $billingAddress;
        $this->billingAddressIdRef = $billingAddress->getId();
        // Add the billing address to the collection of destinations
        $this->getDestinations()->offsetSet($billingAddress);
        return $this;
    }

    public function getShopRunnerMessage()
    {
        return $this->shopRunnerMessage;
    }

    public function setShopRunnerMessage($shopRunnerMessage)
    {
        $this->shopRunnerMessage = $this->normalizeWhitespace($shopRunnerMessage);
        return $this;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $cleaned = $this->cleanString($currency, 3);
        if (strlen($cleaned) === 3) {
            $this->currency = $currency;
        }
        return $this;
    }

    public function getAssociateName()
    {
        return $this->associateName;
    }

    public function setAssociateName($associateName)
    {
        $this->associateName = $associateName;
        return $this;
    }

    public function getAssociateNumber()
    {
        return $this->associateNumber;
    }

    public function setAssociateNumber($associateNumber)
    {
        $this->associateNumber = $associateNumber;
        return $this;
    }

    public function getAssociateStore()
    {
        return $this->associateStore;
    }

    public function setAssociateStore($associateStore)
    {
        $this->associateStore = $associateStore;
        return $this;
    }

    public function getTaxHasErrors()
    {
        return $this->taxHeader;
    }

    public function setTaxHasErrors($taxHeader)
    {
        $this->taxHeader = (bool) $taxHeader;
        return $this;
    }

    public function getPrintedCatalogCode()
    {
        return $this->printedCatalogCode;
    }

    public function setPrintedCatalogCode($printedCatalogCode)
    {
        $this->printedCatalogCode = $printedCatalogCode;
        return $this;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $cleaned = $this->cleanString($locale, 5);
        // local must match pattern two character language code, and underscore (_),
        // two character country code
        $this->locale = (preg_match('#^[a-z]{2}_[a-z]{2}$#i', $cleaned)) ? $cleaned : null;
        return $this;
    }

    public function getDashboardRepId()
    {
        return $this->dashboardRepId;
    }

    public function setDashboardRepId($dashboardRepId)
    {
        $this->dashboardRepId = $dashboardRepId;
        return $this;
    }

    public function getOrderSource()
    {
        return $this->orderSource;
    }

    public function setOrderSource($orderSource)
    {
        $this->orderSource = $orderSource;
        return $this;
    }

    public function getOrderSourceType()
    {
        return $this->orderSourceType;
    }

    public function setOrderSourceType($orderSourceType)
    {
        $this->orderSourceType = $orderSourceType;
        return $this;
    }

    public function getOrderHistoryUrl()
    {
        return $this->orderHistoryUrl;
    }

    public function setOrderHistoryUrl($orderHistoryUrl)
    {
        $this->orderHistoryUrl = $orderHistoryUrl;
        return $this;
    }

    public function getVatInclusivePricing()
    {
        return $this->vatInclusivePricing;
    }

    public function setVatInclusivePricing($vatInclusivePricing)
    {
        $this->vatInclusivePricing = (bool) $vatInclusivePricing;
        return $this;
    }
    public function getOrderTotal()
    {
        return $this->orderTotal;
    }

    public function setOrderTotal($orderTotal)
    {
        $this->orderTotal = $this->sanitizeAmount($orderTotal);
        return $this;
    }

    protected function setupSubPayloads()
    {
        $this->loyaltyPrograms = $this->buildPayloadForInterface(
            static::LOYALTY_PROGRAM_ITERABLE_INTERFACE
        );
        $this->orderItems = $this->buildPayloadForInterface(
            static::ORDER_ITEM_ITERABLE_INTERFACE
        );
        $this->payments = $this->buildPayloadForInterface(
            static::PAYMENT_ITERABLE_INTERFACE
        );
        $this->shipGroups = $this->buildPayloadForInterface(
            static::SHIP_GROUP_ITERABLE_INTERFACE
        );
        $this->destinations = $this->buildPayloadForInterface(
            static::DESTINATION_ITERABLE_INTERFACE
        );
        $this->itemRelationships = $this->buildPayloadForInterface(
            static::ITEM_RELATIONSHIP_ITERABLE_INTERFACE
        );
        $this->holds = $this->buildPayloadForInterface(
            static::ORDER_HOLD_ITERABLE_INTERFACE
        );
        $this->customAttributes = $this->buildPayloadForInterface(
            static::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE
        );
        $this->templates = $this->buildPayloadForInterface(
            static::TEMPLATE_ITERABLE_INTERFACE
        );
        $this->orderContextCustomAttributes = $this->buildPayloadForInterface(
            static::ORDER_CONTEXT_CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE
        );
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        return $this->deserializeDateTimeValues($xpath)->deserializeExtraOrderContext($xpath);
    }

    /**
     * Deserialize date time values as DateTime objects.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeDateTimeValues(DOMXPath $xpath)
    {
        $dateProperties = [
            'createTime' => 'string(x:Order/x:CreateTime)',
            'dateOfBirth' => 'string(x:Order/x:Customer/x:DateOfBirth)',
        ];
        foreach ($dateProperties as $prop => $extractPath) {
            $value = $xpath->evaluate($extractPath);
            $this->$prop = $value ? new DateTime($value) : null;
        }
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializeOrder() . $this->serializeOrderContext();
    }

    /**
     * Serialize the order details.
     *
     * @return string
     */
    protected function serializeOrder()
    {
        return "<Order customerOrderId='{$this->xmlEncode($this->getOrderId())}' levelOfService='{$this->xmlEncode($this->getLevelOfService())}'>"
            . $this->serializeOrderCustomer()
            . "<CreateTime>{$this->getCreateTime()->format('c')}</CreateTime>"
            . $this->getOrderItems()->serialize()
            . '<Shipping>'
            . $this->getShipGroups()->serialize()
            . $this->getDestinations()->serialize()
            . '</Shipping>'
            . '<Payment>'
            . "<BillingAddress ref='{$this->xmlEncode($this->getBillingAddress()->getId())}' />"
            . $this->getPayments()->serialize()
            . '</Payment>'
            . $this->serializeOptionalXmlEncodedValue('ShopRunnerMessage', $this->getShopRunnerMessage())
            . "<Currency>{$this->xmlEncode($this->getCurrency())}</Currency>"
            . $this->serializeAssociate()
            . $this->serializeTaxHeader()
            . $this->serializeOptionalXmlEncodedValue('PrintedCatalogCode', $this->getPrintedCatalogCode())
            . "<Locale>{$this->xmlEncode($this->getLocale())}</Locale>"
            . $this->getItemRelationships()->serialize()
            . $this->serializeOptionalXmlEncodedValue('DashboardRepId', $this->getDashboardRepId())
            . $this->serializeOrderSource()
            . $this->getHolds()->serialize()
            . $this->getCustomAttributes()->serialize()
            . $this->getTemplates()->serialize()
            . ($this->getOrderHistoryUrl() ? "<OrderHistoryUrl>{$this->xmlEncode($this->getOrderHistoryUrl())}</OrderHistoryUrl>" : "")
            . $this->serializeVatInclusivePricing()
            . $this->serializeOptionalAmount('OrderTotal', $this->getOrderTotal())
            . '</Order>';
    }

    /**
     * Serialize store associate details.
     *
     * @return string
     */
    protected function serializeAssociate()
    {
        if (!is_null($this->getAssociateNumber())) {
            return '<Associate>'
                 . "<Name>{$this->xmlEncode($this->getAssociateName())}</Name>"
                 . "<Number>{$this->xmlEncode($this->getAssociateNumber())}</Number>"
                 . "<Store>{$this->xmlEncode($this->getAssociateStore())}</Store>"
                 . '</Associate>';
        }
        return '';
    }

    /**
     * Serialize tax headers.
     *
     * @return string
     */
    protected function serializeTaxHeader()
    {
        $taxHasErrors = $this->getTaxHasErrors();
        if (!is_null($taxHasErrors)) {
            return "<TaxHeader><Error>{$this->convertBooleanToString($taxHasErrors)}</Error></TaxHeader>";
        }
        return '';
    }

    /**
     * Serialize the order source and order source type.
     *
     * @return string
     */
    protected function serializeOrderSource()
    {
        $orso = $this->getOrderSource();
        if (!is_null($orso)) {
            return sprintf('<OrderSource type="%s">', $this->xmlEncode($this->getOrderSourceType()))
                . $this->xmlEncode($orso)
                . '</OrderSource>';
        }
        return '';
    }

    /**
     * Serialize if the order uses vat inclusive pricing.
     *
     * @return string
     */
    protected function serializeVatInclusivePricing()
    {
        $vatInclusive = $this->getVatInclusivePricing();
        if (!is_null($vatInclusive)) {
            return "<VATInclusivePricing>{$this->convertBooleanToString($vatInclusive)}</VATInclusivePricing>";
        }
        return '';
    }

    protected function getRootAttributes()
    {
        return array_filter([
            'orderType' => $this->getOrderType(),
            'requestId' => $this->getRequestId(),
            'testType' => $this->getTestType(),
            'xmlns' => $this->getXmlNamespace(),
        ]);
    }

    protected function getPersonNameRootNodeName()
    {
        return self::PERSON_NAME_ROOT_NODE;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    protected function getRootNodeName()
    {
        return self::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
