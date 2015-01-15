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

interface ICustomizationInstruction extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Key identifying the type of customization. Must be unique among a related
     * set of customization instructions. Not necessarily intended for customer
     * display.
     *
     * restrictions: string length <= 20
     * @return string
     */
    public function getKey();

    /**
     * @param string
     * @return self
     */
    public function setKey($key);

    /**
     * Information associated with the instruction. The value may be encoded
     * and not necessarily intended for customer display.
     *
     * restrictions: string length <= 254
     * @return string
     */
    public function getValue();

    /**
     * @param string
     * @return self
     */
    public function setValue($value);

    /**
     * Customer facing string identifying the type of customization.
     *
     * restrictions: optional, string with length <= 254
     * @return string
     */
    public function getDisplayTitle();

    /**
     * @param string
     * @return self
     */
    public function setDisplayTitle($displayTitle);

    /**
     * Customer facing value associated with the instruction.
     *
     * restrictions: optional, string with length <= 254
     * @return string
     */
    public function getDisplayValue();

    /**
     * @param string
     * @return self
     */
    public function setDisplayValue($displayValue);
}
