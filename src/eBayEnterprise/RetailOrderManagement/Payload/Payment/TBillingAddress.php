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

trait TBillingAddress
{
    /** @var array */
    protected $billingLines;
    /** @var string */
    protected $billingCity;
    /** @var string */
    protected $billingMainDivision;
    /** @var string */
    protected $billingCountryCode;
    /** @var string */
    protected $billingPostalCode;

    public function getBillingLines()
    {
        return is_array($this->billingLines) ? implode("\n", $this->billingLines) : null;
    }

    public function setBillingLines($lines)
    {
        $this->billingLines = $this->cleanAddressLines($lines);
        return $this;
    }

    /**
     * Make sure we have max 4 address lines of 70 chars max
     *
     * If there are more than 4 lines concatenate all extra lines with the 4th line.
     *
     * Truncate any lines to 70 chars max.
     *
     * @param string $lines
     * @return array or null
     */
    abstract protected function cleanAddressLines($lines);

    /**
     * Aggregate the billing address lines into the BillingAddress node
     *
     * @return string
     */
    protected function serializeBillingAddress()
    {
        $lines = [];
        $billingLines = is_array($this->billingLines) ? $this->billingLines : [];
        $idx = 0;
        foreach ($billingLines as $line) {
            $idx++;
            $lines[] = sprintf(
                '<Line%d>%s</Line%1$d>',
                $idx,
                $this->xmlEncode($line)
            );
        }

        return sprintf(
            '<BillingAddress>%s<City>%s</City>%s<CountryCode>%s</CountryCode>%s</BillingAddress>',
            implode('', $lines),
            $this->xmlEncode($this->getBillingCity()),
            $this->serializeOptionalXmlEncodedValue('MainDivision', $this->getBillingMainDivision()),
            $this->xmlEncode($this->getBillingCountryCode()),
            $this->serializeOptionalXmlEncodedValue('PostalCode', $this->getBillingPostalCode())
        );
    }

    public function getBillingCity()
    {
        return $this->billingCity;
    }

    public function setBillingCity($city)
    {
        $this->billingCity = $this->cleanString($city, 35);
        return $this;
    }

    public function getBillingMainDivision()
    {
        return $this->billingMainDivision;
    }

    public function setBillingMainDivision($div)
    {
        $this->billingMainDivision = $this->cleanString($div, 35);
        return $this;
    }

    public function getBillingCountryCode()
    {
        return $this->billingCountryCode;
    }

    public function setBillingCountryCode($code)
    {
        $cleaned = $this->cleanString($code, 40);
        $this->billingCountryCode = strlen($cleaned) >= 2 ? $cleaned : null;
        return $this;
    }

    public function getBillingPostalCode()
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode($code)
    {
        $this->billingPostalCode = $this->cleanString($code, 15);
        return $this;
    }

    /**
     * Trim any white space and return the resulting string truncating to $maxLength.
     *
     * Return null if the result is an empty string or not a string
     *
     * @param string $string
     * @param int $maxLength
     * @return string or null
     */
    abstract protected function cleanString($string, $maxLength);

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
