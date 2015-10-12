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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

trait TCurrencyCode
{
    /** @var string * */
    protected $currencyCode;

    /**
     * return Serialized Currency Code
     * @return string
     */
    protected function serializeCurrencyCode()
    {
        return "<CurrencyCode>{$this->xmlEncode($this->getCurrencyCode())}</CurrencyCode>";
    }

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCode()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalGetExpressCheckoutRequest
        return $this->currencyCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($code)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalGetExpressCheckoutRequest
        $cleaned = substr(trim($code), 0, 3);
        $this->currencyCode = (strlen($cleaned) < 3) ? null : $cleaned;
        return $this;
    }

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
