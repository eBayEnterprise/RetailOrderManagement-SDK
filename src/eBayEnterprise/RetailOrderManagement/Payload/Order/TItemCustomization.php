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

use DOMXPath;

trait TItemCustomization
{
    use TCustomizationContainer;

    /** @var IPriceGroup */
    protected $customizationBasePrice;
    /** @var string */
    protected $customizationDisplayUrl;

    public function getEmptyCustomizationBasePrice()
    {
        return $this->buildPayloadForInterface(self::CUSTOMIZATION_BASE_PRICE_GROUP_INTERFACE);
    }

    public function getCustomizationBasePrice()
    {
        return $this->customizationBasePrice;
    }

    public function setCustomizationBasePrice(IPriceGroup $customizationBasePrice)
    {
        $this->customizationBasePrice = $customizationBasePrice;
        return $this;
    }

    public function getCustomizationDisplayUrl()
    {
        return $this->customizationDisplayUrl;
    }

    public function setCustomizationDisplayUrl($customizationDisplayUrl)
    {
        $this->customizationDisplayUrl = $customizationDisplayUrl;
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
            return '<Customization>'
                . $this->getCustomizations()->serialize()
                . ($this->getCustomizationBasePrice() ? $this->getCustomizationBasePrice()->setRootNodeName('BasePrice')->serialize() : '')
                . $this->serializeOptionalXmlEncodedValue('DisplayUrl', $this->getCustomizationDisplayUrl())
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
            $this->setCustomizationBasePrice(
                $this->getEmptyCustomizationBasePrice()->deserialize($priceNode->C14N())
            );
        }
        return $this;
    }
}
