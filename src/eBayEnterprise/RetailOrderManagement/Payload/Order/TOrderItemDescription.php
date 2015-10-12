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

trait TOrderItemDescription
{
    use TProductDescription;

    /** @var string */
    protected $color;
    /** @var string */
    protected $colorId;
    /** @var string */
    protected $size;
    /** @var string */
    protected $sizeId;

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }

    public function getColorId()
    {
        return $this->colorId;
    }

    public function setColorId($colorId)
    {
        $this->colorId = $colorId;
        return $this;
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;
        return $this;
    }

    public function getSizeId()
    {
        return $this->sizeId;
    }

    public function setSizeId($sizeId)
    {
        $this->sizeId = $sizeId;
        return $this;
    }

    /**
     * Serialize the order item description as XML
     *
     * @return string
     */
    protected function serializeOrderItemDescription()
    {
        //
        return $this->hasItemDescription()
            ? sprintf(
                '<Description>%s%s%s</Description>',
                $this->serializeProductDescription(),
                $this->serializeColor(),
                $this->serializeSize()
            )
            : '';
    }

    /**
     * Is there sufficient data to include an order item description. Must
     * have at least a title and description.
     *
     * @return bool
     */
    protected function hasItemDescription()
    {
        return !is_null($this->getDescription());
    }

    /**
     * Serialize the color. If a color is not set, will return an empty string.
     *
     * @return string
     */
    protected function serializeColor()
    {
        return $this->serializeSizeColor('getColor', 'getColorId', 'Color');
    }

    /**
     * Serialize the size. If a size is not set, will return an empty string.
     *
     * @return string
     */
    protected function serializeSize()
    {
        return $this->serializeSizeColor('getSize', 'getSizeId', 'Size');
    }

    /**
     * Abstraction of size and color serialization. When the value getter
     * returns a value, will serialize it using the node name give. If the
     * id getter returns a value, will include the id attribute on the element.
     *
     * @param string Name of the method to return the value to serialize
     * @param string Name of the method to return the id attribute value.
     * @param string Name of the XML element node to wrap the data
     * @return string
     */
    protected function serializeSizeColor($valGetter, $idGetter, $nodeName)
    {
        $val = $this->$valGetter();
        if (!is_null($val)) {
            $id = $this->$idGetter();
            return sprintf(
                '<%s %s>%s</%1$s>',
                $nodeName,
                ($id ? "id='{$this->xmlEncode($id)}'" : ''),
                $this->xmlEncode($val)
            );
        }
        return '';
    }
}
