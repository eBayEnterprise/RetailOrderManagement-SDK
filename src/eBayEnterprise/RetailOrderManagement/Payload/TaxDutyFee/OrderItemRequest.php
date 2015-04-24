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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class OrderItemRequest implements IOrderItemRequest
{
    use TPayload, TOrderItem, TFeeContainer, TGifting, TCustomizationContainer;

    const ROOT_NODE = 'OrderItem';

    /** @var IPhysicalAddress */
    protected $adminOrigin;
    /** @var IPhysicalAddress */
    protected $shippingOrigin;
    /** @var string */
    protected $manufacturingCountryCode;
    /** @var IMerchandisePriceGroup */
    protected $merchandisePricing;
    /** @var IPriceGroup */
    protected $shippingPricing;
    /** @var IDutyPriceGroup */
    protected $dutyPricing;

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
            'lineNumber' => 'string(@lineNumber)',
            'itemId' => 'string(x:ItemId)',
            'quantity' => 'number(x:Quantity)',
        ];
        $this->optionalExtractionPaths = [
            'description' => 'x:ItemDesc',
            'screenSize' => 'x:ScreenSize',
            'htsCode' => 'x:HTSCode',
            'manufacturingCountryCode' => 'x:Origins/x:ManufacturingCountryCode',
            'giftId' => 'x:Gifting/@id',
            'giftItemId' => 'x:Gifting/x:ItemId',
            'giftDescription' => 'x:Gifting/x:ItemDesc',
        ];
        $this->subpayloadExtractionPaths = [
            'adminOrigin' => 'x:Origins/x:AdminOrigin',
            'shippingOrigin' => 'x:Origins/x:ShippingOrigin',
            'fees' => 'x:Pricing/x:Fees',
            'customizations' => 'x:Customization/x:CustomFeatureList',
        ];

        $this->adminOrigin = $this->getEmptyPhysicalAddress()->setOriginAddressNodeName('AdminOrigin');
        $this->shippingOrigin = $this->getEmptyPhysicalAddress()->setOriginAddressNodeName('ShippingOrigin');
        $this->fees = $this->buildPayloadForInterface(
            static::FEE_ITERABLE_INTERFACE
        );
        $this->customizations = $this->buildPayloadForInterface(
            static::CUSTOMIZATION_ITERABLE_INTERFACE
        );
        $this->merchandisePricing = $this->getEmptyMerchandisePriceGroup();
    }

    public function getEmptyPhysicalAddress()
    {
        return $this->buildPayloadForInterface(static::PHYSICAL_ADDRESS_INTERFACE);
    }

    public function getEmptyMerchandisePriceGroup()
    {
        return $this->buildPayloadForInterface(static::MERCHANDISE_PRICE_GROUP_INTERFACE);
    }

    public function getEmptyShippingPriceGroup()
    {
        return $this->buildPayloadForInterface(static::SHIPPING_PRICE_GROUP_INTERFACE);
    }

    public function getEmptyDutyPriceGroup()
    {
        return $this->buildPayloadForInterface(static::DUTY_PRICE_GROUP_INTERFACE);
    }

    public function getAdminOrigin()
    {
        return $this->adminOrigin;
    }

    public function setAdminOrigin(IPhysicalAddress $address)
    {
        $this->adminOrigin = $address;
        return $this;
    }

    public function getShippingOrigin()
    {
        return $this->shippingOrigin;
    }

    public function setShippingOrigin(IPhysicalAddress $address)
    {
        $this->shippingOrigin = $address;
        return $this;
    }

    public function getManufacturingCountryCode()
    {
        return $this->manufacturingCountryCode;
    }

    public function setManufacturingCountryCode($code)
    {
        $this->manufacturingCountryCode = $code;
        return $this;
    }

    public function getMerchandisePricing()
    {
        return $this->merchandisePricing;
    }

    public function setMerchandisePricing(IMerchandisePriceGroup $merchandisePricing)
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

    public function setDutyPricing(IDutyPriceGroup $dutyPricing)
    {
        $this->dutyPricing = $dutyPricing;
        return $this;
    }

    public function getGiftPricing()
    {
        return $this->giftPricing;
    }

    public function setGiftPricing(IMerchandisePriceGroup $giftPricing)
    {
        $this->giftPricing = $giftPricing;
        return $this;
    }

    protected function serializeOrigins()
    {
        return '<Origins>'
            . $this->getAdminOrigin()->serialize()
            . $this->getShippingOrigin()->serialize()
            . $this->serializeOptionalValue('ManufacturingCountryCode', $this->getManufacturingCountryCode())
            . '</Origins>';
    }

    protected function serializeContents()
    {
        return "<ItemId>{$this->getItemId()}</ItemId>"
            . $this->serializeOptionalValue('ItemDesc', $this->getDescription())
            . $this->serializeOptionalValue('HTSCode', $this->getHtsCode())
            . $this->serializeOptionalValue('ScreenSize', $this->getScreenSize())
            . $this->serializeOrigins()
            . "<Quantity>{$this->getQuantity()}</Quantity>"
            . $this->serializePricing()
            . $this->serializeGifting()
            . $this->serializeCustomizations();
    }

    protected function getRootAttributes()
    {
        return array_filter([
            'lineNumber' => $this->getLineNumber(),
        ]);
    }

    protected function deserializeExtra($serializePayload)
    {
        $xpath = $this->getPayloadAsXPath($serializePayload);
        return $this->deserializeItemPrices($xpath)
            ->deserializeGiftPricing($xpath)
            ->deserializeCustomizationBasePrice($xpath);
    }
}
