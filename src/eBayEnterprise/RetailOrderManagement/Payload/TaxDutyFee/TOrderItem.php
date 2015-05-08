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

trait TOrderItem
{
    /** @var string */
    protected $lineNumber;
    /** @var string */
    protected $itemId;
    /** @var float */
    protected $quantity;
    /** @var string */
    protected $screenSize;
    /** @var string */
    protected $description;
    /** @var string */
    protected $htsCode;

    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
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
        $this->quantity = (int) $quantity;
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

    public function getHtsCode()
    {
        return $this->htsCode;
    }

    public function setHtsCode($htsCode)
    {
        $this->htsCode = $htsCode;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
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
        $fees = $this->getFees();
        return '<Pricing>'
            . $this->getMerchandisePricing()->setRootNodeName('Merchandise')->serialize()
            . ($shippingPricing ? $shippingPricing->setRootNodeName('Shipping')->serialize() : '')
            . ($dutyPricing ? $dutyPricing->setRootNodeName('Duty')->serialize() : '')
            . (count($fees) ? $fees->serialize() : '')
            . '</Pricing>';
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
        $priceGroupGetters = [
            'merchandisePricing' => 'getEmptyMerchandisePriceGroup',
            'shippingPricing' => 'getEmptyShippingPriceGroup',
            'dutyPricing' => 'getEmptyDutyPriceGroup',
        ];
        foreach ($itemPrices as $property => $extractionPath) {
            $priceNode = $xpath->query($extractionPath)->item(0);
            if ($priceNode) {
                $getter = $priceGroupGetters[$property];
                $this->$property = $this->$getter()->deserialize($priceNode->C14N());
            }
        }
        return $this;
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
