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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IFee extends IPayload, ITaxContainer
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Type of fee being charged.
     *
     * @return string
     */
    public function getType();

    /**
     * @param string
     * @return self
     */
    public function setType($type);

    /**
     * Additional description of the fee.
     *
     * restrictions: optional
     * @return string
     */
    public function getDescription();

    /**
     * @param string
     * @return self
     */
    public function setDescription($description);

    /**
     * Currency amount of the fee.
     *
     * restrictions: two decimal, non-negative
     * @return float
     */
    public function getAmount();

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);

    /**
     * Item id associated with the fee.
     *
     * restrictions
     * @return string
     */
    public function getItemId();

    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId);
}
