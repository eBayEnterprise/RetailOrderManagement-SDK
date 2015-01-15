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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

interface IOrderItemDescription extends IProductDescription
{
    /**
     * Color of the item
     *
     * @return string
     */
    public function getColor();
    /**
     * @param string
     * @return self
     */
    public function setColor($color);
    /**
     * Unique code for the color
     *
     * @return string
     */
    public function getColorId();
    /**
     * @param string
     * @return self
     */
    public function setColorId($colorId);
    /**
     * Size of the item
     *
     * @return string
     */
    public function getSize();
    /**
     * @param string
     * @return self
     */
    public function setSize($size);
    /**
     * Unique code for the size
     *
     * @return string
     */
    public function getSizeId();
    /**
     * @param string
     * @return self
     */
    public function setSizeId($sizeId);
}
