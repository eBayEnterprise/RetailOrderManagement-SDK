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

interface IProductPrice
{
    /**
     * Line item total amount.
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getAmount();
    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);
    /**
     * Any remainder's in line item calculations due to rounding errors.
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getRemainder();
    /**
     * @param float
     * @return self
     */
    public function setRemainder($remainder);
    /**
     * Price for a single unit of the item.
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getUnitPrice();
    /**
     * @param float
     * @return self
     */
    public function setUnitPrice($unitPrice);
}
