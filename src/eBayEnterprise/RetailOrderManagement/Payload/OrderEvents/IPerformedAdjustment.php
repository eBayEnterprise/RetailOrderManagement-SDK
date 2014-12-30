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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IPerformedAdjustment extends IPayload
{
    const ROOT_NODE = 'Adjustment';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * String to display.
     * @return string
     */
    public function getDisplay();
    /**
     * @param string
     * @return self
     */
    public function setDisplay($display);
    /**
     * Type of adjustment.
     * @return string
     */
    public function getType();
    /**
     * @param string
     * @return self
     */
    public function setType($type);
}
