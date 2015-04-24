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

trait TTaxedCustomizationContainer
{
    /** @var IPriceGroup */
    protected $customizationBasePrice;
    /** @var ICustomizationIterable */
    protected $customizations;

    public function getCustomizations()
    {
        return $this->customizations;
    }

    public function setCustomizations(ITaxedCustomizationIterable $customizations)
    {
        $this->customizations = $customizations;
        return $this;
    }

    public function getEmptyCustomizationBasePrice()
    {
        return $this->buildPayloadForInterface(self::CUSTOMIZATION_BASE_PRICE_GROUP_INTERFACE);
    }

    public function getCustomizationBasePricing()
    {
        return $this->customizationBasePrice;
    }

    public function setCustomizationBasePricing(ITaxedMerchandisePriceGroup $customizationBasePrice)
    {
        $this->customizationBasePrice = $customizationBasePrice;
        return $this;
    }

    /**
     * If customizations are present for the item, serialize the customizations
     * and any base pricing information included.
     *
     * @return string
     */
    protected function serializeCustomizations()
    {
        if ($this->getCustomizations()->count()) {
            $basePricing = $this->getCustomizationBasePricing();
            return '<Customization>'
                . $this->getCustomizations()->serialize()
                . ($basePricing ? $basePricing->setRootNodeName('BasePrice')->serialize() : '')
                . '</Customization>';
        }
        return '';
    }

    /**
     * If a base price for customization is included in the serialized data,
     * provided in the DOMXPath, create a price group for it and deserialize
     * the data into it.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeCustomizationBasePrice(DOMXPath $xpath)
    {
        $priceNode = $xpath->query('x:Customization/x:BasePrice')->item(0);
        if ($priceNode) {
            $this->setCustomizationBasePricing(
                $this->getEmptyCustomizationBasePrice()->deserialize($priceNode->C14N())
            );
        }
        return $this;
    }
}
