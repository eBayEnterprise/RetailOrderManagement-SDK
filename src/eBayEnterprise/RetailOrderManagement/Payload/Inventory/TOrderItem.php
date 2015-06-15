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

trait TOrderItem
{
    /** @var int */
    protected $quantity;
    /** @var bool */
    protected $giftWrapRequested;

    /**
     * Specifies the number of items being ordered.
     *
     * restrictions: 1 <= int <= 99999
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int
     * @return self
     */
    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }

    /**
     * True if the shopper requested gift wrap for this line item.
     *
     * restrictions: length <= 20
     * @return bool
     */
    public function getGiftWrapRequested()
    {
        return $this->giftWrapRequested;
    }

    /**
     * @param bool
     * @return self
     */
    public function setGiftWrapRequested($giftWrapRequested)
    {
        $this->giftWrapRequested = is_bool($giftWrapRequested) ? $giftWrapRequested : null;
        return $this;
    }

    protected function serializeGiftWrapRequested()
    {
        return is_null($this->getGiftWrapRequested()) ? '' :
            "<GiftwrapRequested>{$this->convertBooleanToString($this->getGiftWrapRequested())}</GiftwrapRequested>";
    }

    protected function serializeQuantity()
    {
        $quantity = $this->sanitizeAmount($this->getQuantity());
        return is_null($quantity) ? '' : '<Quantity>' . (int) $quantity . '</Quantity>';
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
