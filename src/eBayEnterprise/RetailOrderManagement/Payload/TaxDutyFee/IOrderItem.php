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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IIdentity;

interface IOrderItem extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Web order line number assigned by the webstore.
     *
     * restrictions: string with length >= 1
     * @return string
     */
    public function getLineNumber();

    /**
     * @param string
     * @return self
     */
    public function setLineNumber($lineNumber);

    /**
     * Unique id used to identify the item. A SKU.
     *
     * restrictions: string with length >= 1 and <= 20
     * @return string
     */
    public function getItemId();

    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId);

    /**
     * Quantity of the item ordered.
     *
     * @return int
     */
    public function getQuantity();

    /**
     * @param int
     * @return self
     */
    public function setQuantity($quantity);

    /**
     * Customer facing description of the item.
     *
     * restrictions: string with length <= 20
     * @return string
     */
    public function getDescription();

    /**
     * @param string
     * @return self
     */
    public function setDescription($description);

    /**
     * Size of item screen in inches.
     * Used for EHF Environment Handling Fee calculations.
     *
     * restrictions: optional
     * @return float
     */
    public function getScreenSize();

    /**
     * @param float
     * @return self
     */
    public function setScreenSize($screenSize);

    /**
     * Code used for duty calculation purposes.
     *
     * restrictions: max length 20, optional
     * @return string
     */
    public function getHtsCode();

    /**
     * @param string
     * @return self
     */
    public function setHtsCode($htsCode);
}
