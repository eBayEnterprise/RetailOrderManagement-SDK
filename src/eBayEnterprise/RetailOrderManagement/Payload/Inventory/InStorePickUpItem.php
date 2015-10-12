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
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use Psr\Log\LoggerInterface;

/**
 * Order line item in a checkout inventory API inventory details request or allocation request.
 */
class InStorePickUpItem implements IInStorePickUpItem
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
            'storeFrontId' => 'string(x:InStorePickupDetails/x:StoreFrontId)',
            'storeFrontName' => 'string(x:InStorePickupDetails/x:StoreFrontName)',
            'city' => 'string(x:InStorePickupDetails/x:StoreFrontAddress/x:City)',
            'countryCode' => 'string(x:InStorePickupDetails/x:StoreFrontAddress/x:CountryCode)',
            'shippingMethodCarrierType' => 'string(x:InStorePickupDetails/x:StoreFrontAddress)',
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'x:InStorePickupDetails/x:StoreFrontAddress/x:MainDivision',
            'postalCode' => 'x:InStorePickupDetails/x:StoreFrontAddress/x:PostalCode',
        ];
        $this->booleanExtractionPaths = [
            'giftWrapRequested' => 'string(x:GiftwrapRequested)',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:InStorePickupDetails/x:StoreFrontAddress/*[starts-with(name(), "Line")]'
            ],
        ];
    }

    /**
     * This is the identifier of the store in which the line item will be picked up.
     *
     * restrictions: length <= 100
     * @return string
     */
    public function getStoreFrontId()
    {
        return $this->storeFrontId;
    }

    /**
     * @param string
     * @return self
     */
    public function setStoreFrontId($id)
    {
        $this->id = $this->cleanString($id, 100);
        return $this;
    }

    /**
     * Store Name
     *
     * restrictions: length <= 100
     * @return string
     */
    public function getStoreFrontName()
    {
        return $this->storeFrontName;
    }

    /**
     * @param string
     * @return self
     */
    public function setStoreFrontName($name)
    {
        $this->name = $this->cleanString($name, 100);
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializeQuantity()
            . "<InStorePickupDetails>"
            . "<StoreFrontId>{$this->xmlEncode($this->getStoreFrontId())}</StoreFrontId>"
            . "<StoreFrontName>{$this->xmlEncode($this->getStoreFrontName())}</StoreFrontName>"
            . $this->serializePhysicalAddress()
            . "</InStorePickupDetails>"
            . $this->serializeGiftWrapRequested();
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
        return 'StoreFrontAddress';
    }
}
