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

trait TGiftingBase
{
    /** @var string */
    protected $giftId;
    /** @var string */
    protected $giftItemId;
    /** @var string */
    protected $giftDescription;

    public function getEmptyGiftPriceGroup()
    {
        return $this->buildPayloadForInterface(self::GIFTING_PRICE_GROUP_INTERFACE);
    }

    public function getGiftId()
    {
        return $this->giftId;
    }

    public function setGiftId($id)
    {
        $this->giftId = $this->cleanString($id, 20) ?: null;
        return $this;
    }

    public function getGiftItemId()
    {
        return $this->giftItemId;
    }

    public function setGiftItemId($giftItemId)
    {
        $this->giftItemId = $this->cleanString($giftItemId, 20);
        return $this;
    }

    public function getGiftDescription()
    {
        return $this->giftDescription;
    }

    public function setGiftDescription($description)
    {
        $this->giftDescription = $description;
        return $this;
    }

    protected function serializeGifting()
    {
        if ($this->getGiftItemId()) {
            $pricing = $this->getGiftPricing();
            return "<Gifting {$this->serializeOptionalAttribute('id', $this->xmlEncode($this->getGiftId()))}>"
                . "<ItemId>{$this->xmlEncode($this->getGiftItemId())}</ItemId>"
                . $this->serializeOptionalXmlEncodedValue('ItemDesc', $this->getGiftDescription())
                . ($pricing ? $pricing->setRootNodeName('Pricing')->serialize() : '')
                . '</Gifting>';
        }
        return '';
    }

    /**
     * Extract gifting pricing from the payload in the DOMXPath and, if it is
     * included in the serialized data, deserialize it into a new price group.
     *
     * @return self
     */
    protected function deserializeGiftPricing(DOMXPath $xpath)
    {
        $priceNode = $xpath->query('x:Gifting/x:Pricing')->item(0);
        if ($priceNode) {
            $this->setGiftPricing(
                $this->getEmptyGiftPriceGroup()->deserialize($priceNode->C14N())
            );
        }
        return $this;
    }

    /**
     * @return IAbstractPriceGroup
     */
    abstract public function getGiftPricing();

    /**
     * @return IPayload
     */
    abstract public function buildPayloadForInterface($interface);

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
