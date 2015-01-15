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

interface IPriceGroup extends IPayload, IDiscountContainer, ITaxContainer
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Calculated total of the prices.
     *
     * restrictions: optional
     * @return float
     */
    public function getAmount();

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);

    /**
     * Any remainder left when doing pricing calculations.
     *
     * restrictions: optional
     * @return float
     */
    public function getRemainder();

    /**
     * @param float
     * @return self
     */
    public function setRemainder($remainder);

    /**
     * Individual unit price for the group of pricing.
     *
     * restrictions: optional
     * @return float
     */
    public function getUnitPrice();

    /**
     * @param float
     * @return self
     */
    public function setUnitPrice($unitPrice);

    /**
     * Dynamically set the name of the root node the price group gets serialized
     * with. As this type can represent a variant of pricing information,
     * serializations will vary based upon context.
     *
     * @param string Must be a valid XML node name
     * @return self
     */
    public function setRootNodeName($nodeName);
}
