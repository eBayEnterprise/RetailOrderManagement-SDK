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

trait TCurrency
{
    /** @var string */
    protected $currencyCode;
    /** @var string */
    protected $currencySymbol;

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode($currencyCode)
    {
        $cleaned = substr(trim($currencyCode), 0, 3);
        $this->currencyCode = (strlen($cleaned) < 3) ? null : $cleaned;
        return $this;
    }

    public function getCurrencySymbol()
    {
        return $this->currencySymbol;
    }

    public function setCurrencySymbol($currencySymbol)
    {
        $this->currencySymbol = $currencySymbol;
        return $this;
    }
}
