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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use Psr\Log\LoggerInterface;

/**
 * Order line item in a checkout inventory API inventory details request or allocation request.
 */
class ShippingItem implements IShippingItem
{
    use TPayload, TItem, TAmount, TOrderItem, TPhysicalAddress {
        TPhysicalAddress::getLines as getAddressLines;
        TPhysicalAddress::setLines as setAddressLines;
        TPhysicalAddress::getCity as getAddressCity;
        TPhysicalAddress::setCity as setAddressCity;
        TPhysicalAddress::getMainDivision as getAddressMainDivision;
        TPhysicalAddress::setMainDivision as setAddressMainDivision;
        TPhysicalAddress::getCountryCode as getAddressCountryCode;
        TPhysicalAddress::setCountryCode as setAddressCountryCode;
        TPhysicalAddress::getPostalCode as getAddressPostalCode;
        TPhysicalAddress::setPostalCode as setAddressPostalCode;
    }

    const ROOT_NODE = 'OrderItem';
    const PHYSICAL_ADDRESS_ROOT_NODE = 'ShipToAddress';

    /** @var string */
    protected $shippingMethod;
    /** @var string */
    protected $shippingMethodMode;
    /** @var string */
    protected $shippingMethodDisplayText;

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

        $this->extractionPaths = [
            'itemId' => 'string(@itemId)',
            'id' => 'string(@lineId)',
            'quantity' => 'string(x:Quantity)',
            'city' => 'string(x:ShipmentDetails/x:ShipToAddress/x:City)',
            'countryCode' => 'string(x:ShipmentDetails/x:ShipToAddress/x:CountryCode)',
            'shippingMethod' => 'string(x:ShipmentDetails/x:ShippingMethod)',
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'x:ShipmentDetails/x:ShipToAddress/x:MainDivision',
            'postalCode' => 'x:ShipmentDetails/x:ShipToAddress/x:PostalCode',
            'shippingMethodMode' => 'x:ShipmentDetails/x:ShippingMethod/@mode',
            'shippingMethodDisplayText' => 'x:ShipmentDetails/x:ShippingMethod/@displayText',
        ];
        $this->booleanExtractionPaths = [
            'giftWrapRequested' => 'string(x:GiftwrapRequested)',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:ShipmentDetails/x:ShipToAddress/*[starts-with(name(), "Line")]'
            ],
        ];
    }

    /**
     * Shipping Carrier such as "UPS" or "FEDEX"
     *
     * restrictions: optional, length <= 40
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->shippingMethod;
    }

    /**
     * @param string
     * @return self
     */
    public function setShippingMethod($shippingMethod)
    {
        $this->shippingMethod = $this->cleanString($shippingMethod, 40);
        return $this;
    }

    /**
     * Indicates the carrier method for example Std_GnD or 2Day
     *
     * restrictions: optional, length <= 40
     * @return string
     */
    public function getShippingMethodMode()
    {
        return $this->shippingMethodMode;
    }

    /**
     * @param string
     * @return self
     */
    public function setShippingMethodMode($shippingMethodMode)
    {
        $this->shippingMethodMode = $shippingMethodMode;
        return $this;
    }

    /**
     * Specifies the text to display when the mode is quierried.
     *
     * restrictions: optional
     * @return string
     */
    public function getShippingMethodDisplayText()
    {
        return $this->shippingMethodDisplayText;
    }

    /**
     * @param string
     * @return self
     */
    public function setShippingMethodDisplayText($shippingMethodDisplayText)
    {
        $this->shippingMethodDisplayText = $shippingMethodDisplayText;
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializeQuantity()
            . "<ShipmentDetails>"
            . $this->serializeShippingMethod()
            . $this->serializePhysicalAddress()
            . "</ShipmentDetails>"
            . $this->serializeGiftWrapRequested();
    }

    protected function serializeShippingMethod()
    {
        return "<ShippingMethod "
            . " {$this->serializeOptionalAttribute('mode', $this->xmlEncode($this->getShippingMethodMode()))}"
            . " {$this->serializeOptionalAttribute('displayText', $this->xmlEncode($this->getShippingMethodDisplayText()))}"
            . ">{$this->xmlEncode($this->getShippingMethod())}</ShippingMethod>";
    }

    protected function getRootAttributes()
    {
        return [
            'itemId' => $this->getItemId(),
            'lineId' => $this->getId(),
        ];
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return static::PHYSICAL_ADDRESS_ROOT_NODE;
    }
}
