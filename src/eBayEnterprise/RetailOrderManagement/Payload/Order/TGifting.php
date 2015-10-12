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

trait TGifting
{
    /** @var string */
    protected $giftItemId;
    /** @var IPriceGroup */
    protected $giftPricing;
    /** @var bool */
    protected $includeGiftWrapping;
    /** @var string */
    protected $giftMessageTo;
    /** @var string */
    protected $giftMessageFrom;
    /** @var string */
    protected $giftMessageContent;
    /** @var string */
    protected $giftCardTo;
    /** @var string */
    protected $giftCardFrom;
    /** @var string */
    protected $giftCardMessage;
    /** @var string */
    protected $packslipTo;
    /** @var string */
    protected $packslipFrom;
    /** @var string */
    protected $packslipMessage;
    /** @var string */
    protected $localizedFromLabel;
    /** @var string */
    protected $localizedToLabel;

    public function getEmptyGiftingPriceGroup()
    {
        return $this->buildPayloadForInterface(self::GIFTING_PRICE_GROUP_INTERFACE);
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

    public function getGiftPricing()
    {
        return $this->giftPricing;
    }

    public function setGiftPricing(IPriceGroup $giftPricing)
    {
        $this->giftPricing = $giftPricing;
        return $this;
    }

    public function getIncludeGiftWrapping()
    {
        return $this->includeGiftWrapping;
    }

    public function setIncludeGiftWrapping($includeGiftWrapping)
    {
        $this->includeGiftWrapping = (bool) $includeGiftWrapping;
        return $this;
    }

    public function getGiftMessageTo()
    {
        return $this->giftMessageTo;
    }

    public function setGiftMessageTo($giftMessageTo)
    {
        $this->giftMessageTo = $giftMessageTo;
        return $this;
    }

    public function getGiftMessageFrom()
    {
        return $this->giftMessageFrom;
    }

    public function setGiftMessageFrom($giftMessageFrom)
    {
        $this->giftMessageFrom = $giftMessageFrom;
        return $this;
    }

    public function getGiftMessageContent()
    {
        return $this->giftMessageContent;
    }

    public function setGiftMessageContent($giftMessageContent)
    {
        $this->giftMessageContent = $this->normalizeWhitespace($giftMessageContent);
        return $this;
    }

    public function getGiftCardTo()
    {
        return $this->giftCardTo;
    }

    public function setGiftCardTo($giftCardTo)
    {
        $this->giftCardTo = $giftCardTo;
        return $this;
    }

    public function getGiftCardFrom()
    {
        return $this->giftCardFrom;
    }

    public function setGiftCardFrom($giftCardFrom)
    {
        $this->giftCardFrom = $giftCardFrom;
        return $this;
    }

    public function getGiftCardMessage()
    {
        return $this->giftCardMessage;
    }

    public function setGiftCardMessage($giftCardMessage)
    {
        $this->giftCardMessage = $this->normalizeWhitespace($giftCardMessage);
        return $this;
    }

    public function getPackslipTo()
    {
        return $this->packslipTo;
    }

    public function setPackslipTo($packslipTo)
    {
        $this->packslipTo = $packslipTo;
        return $this;
    }

    public function getPackslipFrom()
    {
        return $this->packslipFrom;
    }

    public function setPackslipFrom($packslipFrom)
    {
        $this->packslipFrom = $packslipFrom;
        return $this;
    }

    public function getPackslipMessage()
    {
        return $this->packslipMessage;
    }

    public function setPackslipMessage($packslipMessage)
    {
        $this->packslipMessage = $this->normalizeWhitespace($packslipMessage);
        return $this;
    }

    public function getLocalizedFromLabel()
    {
        return $this->localizedFromLabel;
    }

    public function setLocalizedFromLabel($localizedFromLabel)
    {
        $this->localizedFromLabel = $localizedFromLabel;
        return $this;
    }

    public function getLocalizedToLabel()
    {
        return $this->localizedToLabel;
    }

    public function setLocalizedToLabel($localizedToLabel)
    {
        $this->localizedToLabel = $localizedToLabel;
        return $this;
    }

    /**
     * Serialize gifting data.
     *
     * @return string
     */
    protected function serializeGifting()
    {
        $serializedData = $this->serializeGift()
            . $this->serializeGiftingGiftCard()
            . $this->serializeGiftingPackslip();
        return $serializedData ? "<Gifting>$serializedData</Gifting>" : '';
    }

    /**
     * Serialize the "Gift" gifting data. Will only be included if there is
     * a gift item id set.
     *
     * @return string
     */
    protected function serializeGift()
    {
        if ($this->getGiftItemId()) {
            $pricing = $this->getGiftPricing();
            return '<Gift>'
                . "<ItemId>{$this->xmlEncode($this->getGiftItemId())}</ItemId>"
                . ($pricing ? $pricing->setRootNodeName('Pricing')->serialize() : '')
                . $this->serializeGiftingGiftMessage()
                . '</Gift>';
        }
        return '';
    }

    /**
     * Serialize the gift message data. Will only be included if there
     * are valid to and from fields.
     *
     * @return string
     */
    protected function serializeGiftingGiftMessage()
    {
        $messageSerialization = $this->serializeGiftingMessage(
            $this->getGiftMessageTo(),
            $this->getGiftMessageFrom(),
            $this->getGiftMessageContent()
        );
        return $messageSerialization ? $messageSerialization : '';
    }

    /**
     * Serialize the gift card message data. Will only be included if there
     * are valid to and from fields.
     *
     * @return string
     */
    protected function serializeGiftingGiftCard()
    {
        $messageSerialization = $this->serializeGiftingMessage(
            $this->getGiftCardTo(),
            $this->getGiftCardFrom(),
            $this->getGiftCardMessage()
        );
        // If the gifting includes gift wrapping, the gift card node
        // must always be included.
        return ($messageSerialization || $this->getIncludeGiftWrapping())
            ? "<GiftCard>$messageSerialization</GiftCard>"
            : '';
    }

    /**
     * Serialize the packslip message data. Will only be included if there
     * are valid to and from fields.
     *
     * @return string
     */
    protected function serializeGiftingPackslip()
    {
        $messageSerialization = $this->serializeGiftingMessage(
            $this->getPackslipTo(),
            $this->getPackslipFrom(),
            $this->getPackslipMessage()
        );
        return $messageSerialization ? "<Packslip>$messageSerialization</Packslip>" : '';
    }

    /**
     * Serialize a gifting message from, to and message. Will only return a
     * serialization if at least a from and to name are given.
     *
     * @param string
     * @param string
     * @param string
     * @return string
     */
    protected function serializeGiftingMessage($toName, $fromName, $messageText)
    {
        $localizedTo = $this->serializeOptionalAttribute(
            'localizedDisplayText',
            $this->xmlEncode($this->getLocalizedToLabel())
        );
        $localizedFrom = $this->serializeOptionalAttribute(
            'localizedDisplayText',
            $this->xmlEncode($this->getLocalizedFromLabel())
        );
        return $toName && $fromName
            ? '<Message>'
                . "<To $localizedTo>{$this->xmlEncode($toName)}</To>"
                . "<From $localizedFrom>{$this->xmlEncode($fromName)}</From>"
                . $this->serializeOptionalXmlEncodedValue('Message', $messageText)
                . '</Message>'
            : '';
    }

    /**
     * Extract gifting pricing from the payload in the DOMXPath and, if it is
     * included in the serialized data, deserialize it into a new price group.
     *
     * @return self
     */
    protected function deserializeGiftPricing(DOMXPath $xpath)
    {
        $priceNode = $xpath->query('x:Gifting/x:Gift/x:Pricing')->item(0);
        if ($priceNode) {
            $this->setGiftPricing(
                $this->getEmptyGiftingPriceGroup()->deserialize($priceNode->C14N())
            );
        }
        return $this;
    }

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
