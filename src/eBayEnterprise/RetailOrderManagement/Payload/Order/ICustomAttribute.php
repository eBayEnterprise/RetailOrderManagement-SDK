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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ICustomAttribute extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Unique key for the attribute
     * @return string
     */
    public function getKey();

    /**
     * @param string
     * @return self
     */
    public function setKey($key);

    /**
     * Value of the attribute. Value will have all whitespaces normalized
     * to a single whitespace character.
     *
     * xsd restrictions: whitespace normalized string
     * @return string
     */
    public function getValue();

    /**
     * @param string
     * @return self
     */
    public function setValue($value);
}
