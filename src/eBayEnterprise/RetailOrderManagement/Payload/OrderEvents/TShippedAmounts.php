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

trait TShippedAmounts
{
    /** @var float */
    protected $shippedAmount;

    public function getShippedAmount()
    {
        return $this->shippedAmount;
    }

    public function setShippedAmount($amount)
    {
        $this->shippedAmount = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * ensure the amount is rounded to two decimal places.
     *
     * @param  mixed any numeric value
     * @return float|null rounded to 2 places, null if amount is not numeric
     */
    abstract protected function sanitizeAmount($amount);
}
