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

trait TAmount
{
    /**
     * serialize an amount as xml without the currency code
     *
     * @param  string name of element containing the amount
     * @param  mixed amount to serialize
     * @return string
     */
    protected function serializeAmount($elementName, $amount)
    {
        return sprintf(
            '<%1$s>%2$s</%1$s>',
            $elementName,
            $this->formatAmount($this->sanitizeAmount($amount))
        );
    }

    /**
     * ensure the amount is rounded to two decimal places.
     *
     * @param  mixed any numeric value
     * @return float|null rounded to 2 places, null if amount is not numeric
     */
    protected function sanitizeAmount($amount)
    {
        if (is_numeric($amount) && !is_nan($amount)) {
            return round($amount, 2, PHP_ROUND_HALF_UP);
        }
        return null;
    }

    /**
     * serialize an amount as xml without the currency code
     *
     * @param  string $elementName name of element containing the amount
     * @param  mixed $amount amount to serialize
     * @param  string $currencyCode currency code for the amount
     * @return string
     */
    protected function serializeCurrencyAmount($elementName, $amount, $currencyCode)
    {
        return sprintf(
            '<%1$s currencyCode="%3$s">%2$s</%1$s>',
            $elementName,
            $this->formatAmount($this->sanitizeAmount($amount)),
            $currencyCode
        );
    }

    /**
     * Consistent formatting of amounts.
     *
     * @param float
     * @return string
     */
    protected function formatAmount($amount)
    {
        return sprintf('%01.2F', $amount);
    }
}
