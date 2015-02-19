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

use DateTime;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderItem implements IOrderItem
{
    use TPayload, TIdentity, TOrderItemDescription, TFeeContainer, TEstimatedDeliveryDate,
        TGifting, TItemCustomization, TCustomAttributeContainer, TNamedDeliveryDate;

    const ROOT_NODE = 'OrderItem';

    /** @var int */
    protected $lineNumber;
    /** @var bool */
    protected $isHiddenGift;
    /** @var string */
    protected $taxAndDutyDisplayType;
    /** @var int */
    protected $subscriptionId;
    /** @var string */
    protected $itemId;
    /** @var float */
    protected $quantity;
    /** @var string */
    protected $screenSize;
    /** @var string */
    protected $department;
    /** @var IPriceGroup */
    protected $merchandisePricing;
    /** @var IPriceGroup */
    protected $shippingPricing;
    /** @var IPriceGroup */
    protected $dutyPricing;
    /** @var string */
    protected $shippingProgram;
    /** @var string */
    protected $shippingProgramAuthToken;
    /** @var string */
    protected $shippingMethod;
    /** @var string */
    protected $shippingMethodDisplayText;
    /** @var IStoreFrontDetails */
    protected $storeFrontDetails;
    /** @var IProxyPickupDetails */
    protected $proxyPickupDetails;
    /** @var string */
    protected $fulfillmentChannel;
    /** @var string */
    protected $deliveryInstructions;
    /** @var string */
    protected $vendorId;
    /** @var string */
    protected $vendorName;
    /** @var string */
    protected $shopRunnerMessage;
    /** @var string */
    protected $serialNumber;
    /** @var string */
    protected $giftRegistryCancelUrl;
    /** @var string */
    protected $reservationId;

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

        $this->extractionPaths = [
            'id' => 'string(@id)',
            'lineNumber' => 'string(@webLineId)',
            'itemId' => 'string(x:ItemId)',
            'quantity' => 'number(x:Quantity)',
            'includeGiftWrapping' => 'boolean(x:Gifting/x:GiftCard)',
        ];
        $this->optionalExtractionPaths = [
            'subscriptionId' => '@subscriptionId',
            'taxAndDutyDisplayType' => '@taxAndDutyDisplay',
            'description' => 'x:Description/x:Description',
            'color' => 'x:Description/x:Color',
            'colorId' => 'x:Description/x:Color/@id',
            'size' => 'x:Description/x:Size',
            'sizeId' => 'x:Description/x:Size/@id',
            'screenSize' => 'x:Description/x:ScreenSize',
            'department' => 'x:Department',
            'shippingProgram' => 'x:ShippingProgram',
            'shippingProgramAuthToken' => 'x:ShippingProgram/@authToken',
            'shippingMethod' => 'x:ShippingMethod',
            'shippingMethodDisplayText' => 'x:ShippingMethod/@displayText',
            'fulfillmentChannel' => 'x:FulfillmentChannel',
            'estimatedDeliveryMode' => 'x:EstimatedDeliveryDate/x:Mode',
            'estimatedDeliveryMessageType' => 'x:EstimatedDeliveryDate/x:MessageType',
            'estimatedDeliveryTemplate' => 'x:EstimatedDeliveryDate/x:Template',
            'namedDeliveryMessage' => 'x:NamedDeliveryDate/x:Message',
            'deliveryInstructions' => 'x:DeliveryInstructions',
            'vendorId' => 'x:VendorId',
            'vendorName' => 'x:VendorName',
            'shopRunnerMessage' => 'x:ShopRunnerMessage',
            'giftItemId' => 'x:Gifting/x:Gift/x:ItemId',
            'giftMessageTo' => 'x:Gifting/x:Gift/x:Message/x:To',
            'giftMessageFrom' => 'x:Gifting/x:Gift/x:Message/x:From',
            'giftMessageContent' => 'x:Gifting/x:Gift/x:Message/x:Message',
            'giftCardTo' => 'x:Gifting/x:GiftCard/x:Message/x:To',
            'giftCardFrom' => 'x:Gifting/x:GiftCard/x:Message/x:From',
            'giftCardMessage' => 'x:Gifting/x:GiftCard/x:Message/x:Message',
            'packslipTo' => 'x:Gifting/x:Packslip/x:Message/x:To',
            'packslipFrom' => 'x:Gifting/x:Packslip/x:Message/x:From',
            'packslipMessage' => 'x:Gifting/x:Packslip/x:Message/x:Message',
            'localizedFromLabel' => 'x:Gifting/*/x:Message/x:From/@localizedDisplayText',
            'localizedToLabel' => 'x:Gifting/*/x:Message/x:To/@localizedDisplayText',
            'customizationDisplayUrl' => 'x:Customization/x:DisplayUrl',
            'serialNumber' => 'x:SerialNumber',
            'giftRegistryCancelUrl' => 'x:GiftRegistryCancelUrl',
            'reservationId' => 'x:ReservationId',
        ];
        $this->subpayloadExtractionPaths = [
            'fees' => 'x:Pricing/x:Fees',
            'customizations' => 'x:Customization/x:Customizations',
            'customAttributes' => 'x:CustomAttributes',
        ];
        $this->booleanExtractionPaths = [
            'isHiddenGift' => 'string(@isHiddenGift)',
        ];

        $this->fees = $this->buildPayloadForInterface(
            self::FEE_ITERABLE_INTERFACE
        );
        $this->customizations = $this->buildPayloadForInterface(
            self::CUSTOMIZATION_ITERABLE_INTERFACE
        );
        $this->merchandisePricing = $this->buildPayloadForInterface(
            self::PRICE_GROUP_INTERFACE
        );
        $this->customAttributes = $this->buildPayloadForInterface(
            self::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE
        );
    }

    public function getEmptyPriceGroup()
    {
        return $this->buildPayloadForInterface(self::PRICE_GROUP_INTERFACE);
    }

    public function getEmptyStoreFrontDetails()
    {
        return $this->buildPayloadForInterface(self::STORE_FRONT_DETAILS_INTERFACE);
    }

    public function getEmptyProxyPickupDetails()
    {
        return $this->buildPayloadForInterface(self::PROXY_PICKUP_DETAILS_INTERFACE);
    }

    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    public function setLineNumber($lineNumber)
    {
        if ($lineNumber <= 999 && $lineNumber > 0) {
            $this->lineNumber = $lineNumber;
        }
        return $this;
    }

    public function getIsHiddenGift()
    {
        return $this->isHiddenGift;
    }

    public function setIsHiddenGift($isHiddenGift)
    {
        $this->isHiddenGift = (bool) $isHiddenGift;
        return $this;
    }

    public function getTaxAndDutyDisplayType()
    {
        return $this->taxAndDutyDisplayType;
    }

    public function setTaxAndDutyDisplayType($taxAndDutyDisplayType)
    {
        $this->taxAndDutyDisplayType = $taxAndDutyDisplayType;
        return $this;
    }

    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    public function setSubscriptionId($subscriptionId)
    {
        if ($subscriptionId <= 999 && $subscriptionId > 0) {
            $this->subscriptionId = $subscriptionId;
        }
        return $this;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $this->cleanString($itemId, 20);
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getScreenSize()
    {
        return $this->screenSize;
    }

    public function setScreenSize($screenSize)
    {
        $this->screenSize = $screenSize;
        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    public function getMerchandisePricing()
    {
        return $this->merchandisePricing;
    }

    public function setMerchandisePricing(IPriceGroup $merchandisePricing)
    {
        $this->merchandisePricing = $merchandisePricing;
        return $this;
    }

    public function getShippingPricing()
    {
        return $this->shippingPricing;
    }

    public function setShippingPricing(IPriceGroup $shippingPricing)
    {
        $this->shippingPricing = $shippingPricing;
        return $this;
    }

    public function getDutyPricing()
    {
        return $this->dutyPricing;
    }

    public function setDutyPricing(IPriceGroup $dutyPricing)
    {
        $this->dutyPricing = $dutyPricing;
        return $this;
    }

    public function getShippingProgram()
    {
        return $this->shippingProgram;
    }

    public function setShippingProgram($shippingProgram)
    {
        $this->shippingProgram = $shippingProgram;
        return $this;
    }

    public function getShippingProgramAuthToken()
    {
        return $this->shippingProgramAuthToken;
    }

    public function setShippingProgramAuthToken($shippingProgramAuthToken)
    {
        $this->shippingProgramAuthToken = $shippingProgramAuthToken;
        return $this;
    }

    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    public function setShippingMethod($shippingMethod)
    {
        $this->shippingMethod = $shippingMethod;
        return $this;
    }

    public function getShippingMethodDisplayText()
    {
        return $this->shippingMethodDisplayText;
    }

    public function setShippingMethodDisplayText($shippingMethodDisplayText)
    {
        $this->shippingMethodDisplayText = $shippingMethodDisplayText;
        return $this;
    }

    public function getStoreFrontDetails()
    {
        return $this->storeFrontDetails;
    }

    public function setStoreFrontDetails(IStoreFrontDetails $storeFrontDetails)
    {
        $this->storeFrontDetails = $storeFrontDetails;
        return $this;
    }

    public function getProxyPickupDetails()
    {
        return $this->proxyPickupDetails;
    }

    public function setProxyPickupDetails(IProxyPickupDetails $proxyPickupDetails)
    {
        $this->proxyPickupDetails = $proxyPickupDetails;
        return $this;
    }

    public function getFulfillmentChannel()
    {
        return $this->fulfillmentChannel;
    }

    public function setFulfillmentChannel($fulfillmentChannel)
    {
        if ($fulfillmentChannel === self::FULFILLMENT_CHANNEL_SHIP_TO_STORE
            || $fulfillmentChannel === self::FULFILLMENT_CHANNEL_STORE_PICK_UP
            || $fulfillmentChannel === self::FULFILLMENT_CHANNEL_SHIP_TO_HOME
            || $fulfillmentChannel === self::FULFILLMENT_CHANNEL_STORE_RESERVATION
        ) {
            $this->fulfillmentChannel = $fulfillmentChannel;
        }
        return $this;
    }

    public function getDeliveryInstructions()
    {
        return $this->deliveryInstructions;
    }

    public function setDeliveryInstructions($deliveryInstructions)
    {
        $this->deliveryInstructions = $this->normalizeWhitespace($deliveryInstructions);
        return $this;
    }

    public function getVendorId()
    {
        return $this->vendorId;
    }

    public function setVendorId($vendorId)
    {
        $this->vendorId = $vendorId;
        return $this;
    }

    public function getVendorName()
    {
        return $this->vendorName;
    }

    public function setVendorName($vendorName)
    {
        $this->vendorName = $vendorName;
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

    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;
        return $this;
    }

    public function getGiftRegistryCancelUrl()
    {
        return $this->giftRegistryCancelUrl;
    }

    public function setGiftRegistryCancelUrl($giftRegistryCancelUrl)
    {
        $this->giftRegistryCancelUrl = $giftRegistryCancelUrl;
        return $this;
    }

    public function getReservationId()
    {
        return $this->reservationId;
    }

    public function setReservationId($reservationId)
    {
        $this->reservationId = $this->cleanString($reservationId, 40);
        return $this;
    }

    protected function serializeContents()
    {
        return "<ItemId>{$this->getItemId()}</ItemId>"
            . "<Quantity>{$this->getQuantity()}</Quantity>"
            . $this->serializeDescription()
            . $this->serializeOptionalValue('Department', $this->getDepartment())
            . $this->serializePricing()
            . $this->serializeShippingProgram()
            . $this->serializeShippingMethod()
            . $this->serializeOptionalSubpayload($this->getStoreFrontDetails())
            . $this->serializeOptionalValue('FulfillmentChannel', $this->getFulfillmentChannel())
            . $this->serializeOptionalSubpayload($this->getProxyPickupDetails())
            . $this->serializeEstimatedDeliveryDate()
            . $this->serializeNamedDeliveryDate()
            . $this->serializeOptionalValue('DeliveryInstructions', $this->getDeliveryInstructions())
            . $this->serializeOptionalValue('VendorId', $this->getVendorId())
            . $this->serializeOptionalValue('VendorName', $this->getVendorName())
            . $this->serializeGifting()
            . $this->serializeOptionalValue('ShopRunnerMessage', $this->getShopRunnerMessage())
            . $this->serializeCustomizations()
            . $this->serializeOptionalValue('SerialNumber', $this->getSerialNumber())
            . $this->getCustomAttributes()->serialize()
            . $this->serializeOptionalValue('GiftRegistryCancelUrl', $this->getGiftRegistryCancelUrl())
            . $this->serializeOptionalValue('ReservationId', $this->getReservationId());
    }

    /**
     * Include an order item description only when the item has a description.
     *
     * @return string
     */
    protected function serializeDescription()
    {
        if ($this->hasItemDescription()) {
            return '<Description>'
                . $this->serializeProductDescription()
                . $this->serializeColor()
                . $this->serializeSize()
                . $this->serializeOptionalValue('ScreenSize', $this->getScreenSize())
                . '</Description>';
        }
        return '';
    }

    /**
     * Serialize order item pricing - merchandise pricing, shipping and duty
     * pricing if they have been set and any fees for the item.
     *
     * @return string
     */
    protected function serializePricing()
    {
        $shippingPricing = $this->getShippingPricing();
        $dutyPricing = $this->getDutyPricing();
        return '<Pricing>'
            . $this->getMerchandisePricing()->setRootNodeName('Merchandise')->serialize()
            . ($shippingPricing ? $shippingPricing->setRootNodeName('Shipping')->serialize() : '')
            . ($dutyPricing ? $dutyPricing->setRootNodeName('Duty')->serialize() : '')
            . $this->getFees()->serialize()
            . '</Pricing>';
    }

    /**
     * Create a shipping program serialization if one is set for the payload.
     *
     * @return string
     */
    protected function serializeShippingProgram()
    {
        if (!is_null($this->getShippingProgram())) {
            return "<ShippingProgram {$this->serializeOptionalAttribute('authToken', $this->getShippingProgramAuthToken())}>"
                . $this->getShippingProgram()
                .'</ShippingProgram>';
        }
        return '';
    }

    /**
     * Create a shipping method serialization if one is set for the payload.
     *
     * @return string
     */
    protected function serializeShippingMethod()
    {
        if (!is_null($this->getShippingMethod())) {
            return "<ShippingMethod {$this->serializeOptionalAttribute('displayText', $this->getShippingMethodDisplayText())}>"
                . $this->getShippingMethod()
                .'</ShippingMethod>';
        }
        return '';
    }

    /**
     * If given an IPayload, return the serialization of the payload. Otherwise,
     * return an empty string, no serialization.
     *
     * @param IPayload
     * @return string
     */
    protected function serializeOptionalSubpayload(IPayload $payload = null)
    {
        return $payload ? $payload->serialize() : '';
    }

    protected function deserializeExtra($serializePayload)
    {
        $xpath = $this->getPayloadAsXPath($serializePayload);
        return $this->deserializeDateTimes($xpath)
            ->deserializeItemPrices($xpath)
            ->deserializeStoreFrontLocation($xpath)
            ->deserializeProxyPickupDetails($xpath)
            ->deserializeGiftPricing($xpath)
            ->deserializeCustomizationBasePrice($xpath);
    }

    /**
     * Deserialize date time objects from the serialized payload in the DOMXPath.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeDateTimes(DOMXPath $xpath)
    {
        $dateProperties = [
            'estimatedDeliveryWindowFrom' => 'string(x:EstimatedDeliveryDate/x:DeliveryWindow/x:From)',
            'estimatedDeliveryWindowTo' => 'string(x:EstimatedDeliveryDate/x:DeliveryWindow/x:To)',
            'estimatedShippingWindowFrom' => 'string(x:EstimatedDeliveryDate/x:ShippingWindow/x:From)',
            'estimatedShippingWindowTo' => 'string(x:EstimatedDeliveryDate/x:ShippingWindow/x:To)',
            'namedDeliveryDate' => 'string(x:NamedDeliveryDate/x:DeliveryDate)',
            'namedDeliveryTimeWindowStart' => 'string(x:NamedDeliveryDate/x:TimeWindowStart)',
            'namedDeliveryTimeWindowEnd' => 'string(x:NamedDeliveryDate/x:TimeWindowEnd)',
        ];
        foreach ($dateProperties as $prop => $extractPath) {
            $value = $xpath->evaluate($extractPath);
            $this->$prop = $value ? new DateTime($value) : null;
        }
        return $this;
    }

    /**
     * Deserialize store front location if one is included in the serialization.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeStoreFrontLocation(DOMXPath $xpath)
    {
        $detailsNode = $xpath->query('x:StoreFrontDetails')->item(0);
        if ($detailsNode) {
            $this->setStoreFrontDetails(
                $this->getEmptyStoreFrontDetails()->deserialize($detailsNode->C14N())
            );
        }
        return $this;
    }

    /**
     * Deserialize proxy pickup details if one is included in the serialization.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeProxyPickupDetails(DOMXPath $xpath)
    {
        $detailsNode = $xpath->query('x:ProxyPickupDetails')->item(0);
        if ($detailsNode) {
            $this->setProxyPickupDetails(
                $this->getEmptyProxyPickupDetails()->deserialize($detailsNode->C14N())
            );
        }
        return $this;
    }

    /**
     * Deserialize price groups from the serialized payload in the DOMXPath.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeItemPrices(DOMXPath $xpath)
    {
        $itemPrices = [
            'merchandisePricing' => 'x:Pricing/x:Merchandise',
            'shippingPricing' => 'x:Pricing/x:Shipping',
            'dutyPricing' => 'x:Pricing/x:Duty',
        ];
        foreach ($itemPrices as $property => $extractionPath) {
            $priceNode = $xpath->query($extractionPath)->item(0);
            if ($priceNode) {
                $this->$property = $this->getEmptyPriceGroup()->deserialize($priceNode->C14N());
            }
        }
        return $this;
    }

    protected function getRootAttributes()
    {
        $isHiddenGift = $this->getIsHiddenGift();
        return array_filter([
            'id' => $this->getId(),
            'webLineId' => $this->getLineNumber(),
            'isHiddenGift' => !is_null($isHiddenGift) ? $this->convertBooleanToString($isHiddenGift) : null,
            'taxAndDutyDisplay' => $this->getTaxAndDutyDisplayType(),
            'subscriptionId' => $this->getSubscriptionId(),
        ]);
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
