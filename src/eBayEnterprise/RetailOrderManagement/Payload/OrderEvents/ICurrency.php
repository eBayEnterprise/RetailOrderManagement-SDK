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

interface ICurrency
{
    /**
     * ISO 4217 code that represents the currency.
     *
     * xsd restriction: 3 character ISO 4217 currency code
     * @return string
     */
    public function getCurrencyCode();
    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($currencyCode);
    /**
     * Symbol to display with with the currency code.
     *
     * @return string
     */
    public function getCurrencySymbol();
    /**
     * @param string
     * @return self
     */
    public function setCurrencySymbol($currencySymbol);
}
