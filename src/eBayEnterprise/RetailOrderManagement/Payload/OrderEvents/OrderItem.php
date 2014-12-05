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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

class OrderItem implements IOrderItem
{
    use TPayload, TOrderItemDescription;

    /**
     * @param IValidatorIterator
     */
    public function __construct(IValidatorIterator $validators)
    {
        $this->extractionPaths = [
            'description' => 'string(Description/Description)',
            'title' => 'string(Description/Title)',
            'lineNumber' => 'number(@webLineId)',
            'itemId' => 'string(@itemId)',
            'quantity' => 'number(@quantity)',
        ];
        $this->optionalExtractionPaths = [
            'color' => 'Description/Color',
            'colorId' => 'Description/Color/@id',
            'size' => 'Description/Size',
            'sizeId' => 'Description/Size/@id',
        ];
        $this->validators = $validators;
    }

    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = is_numeric($lineNumber)
            && ($lineNumber > static::LINE_NUMBER_MIN && $lineNumber < static::LINE_NUMBER_MAX)
            ? (int) $lineNumber
            : null;
        return $this;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $this->cleanString($itemId, static::ITEM_ID_MAX_LENGTH);
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setQuantity($quantity)
    {
        $this->quantity = is_numeric($quantity) ? (float) $quantity : null;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [
            'webLineId' => $this->getLineNumber(),
            'itemId' => $this->getItemId(),
            'quantity' => $this->getQuantity(),
        ];
    }

    protected function serializeContents()
    {
        return $this->serializeOrderItemDescription();
    }
}
