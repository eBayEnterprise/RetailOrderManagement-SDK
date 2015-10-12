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

trait TShippingAddress
{
    /** @var array */
    protected $shipToLines;
    /** @var string */
    protected $shipToCity;
    /** @var string */
    protected $shipToMainDivision;
    /** @var string */
    protected $shipToCountryCode;
    /** @var string */
    protected $shipToPostalCode;

    public function getShipToLines()
    {
        return is_array($this->shipToLines) ? implode("\n", $this->shipToLines) : null;
    }

    public function setShipToLines($lines)
    {
        $this->shipToLines = $this->cleanAddressLines($lines);
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
    protected function cleanAddressLines($lines)
    {
        $finalLines = null;

        if (is_string($lines)) {
            $trimmed = trim($lines);
            $addressLines = preg_split("/\n/", $trimmed, null, PREG_SPLIT_NO_EMPTY);

            $newLines = [];
            foreach ($addressLines as $line) {
                $newLines[] = $this->cleanString($line, 70);
            }

            if (count($newLines) > 4) {
                // concat lines beyond the four allowed down into the last line
                $newLines[3] = $this->cleanString(implode(' ', array_slice($newLines, 3)), 70);
            }

            $finalLines = array_slice($newLines, 0, 4);
        }

        return empty($finalLines) ? null : $finalLines;
    }

    /**
     * Aggregate the shipTo address lines into the ShippingAddress node. This is an optional node.
     *
     * @return string
     */
    protected function serializeShippingAddress()
    {
        $lines = [];
        $idx = 0;
        $shipToLines = is_array($this->shipToLines) ? $this->shipToLines : [];
        foreach ($shipToLines as $line) {
            $idx++;
            $lines[] = sprintf(
                '<Line%d>%s</Line%1$d>',
                $idx,
                $this->xmlEncode($line)
            );
        }
        // If we don't have any address lines, we treat as having no address at all.
        return ($idx) ? $this->buildShippingAddressNode($lines) : '';
    }

    /**
     * Build the Shipping Address Node
     * @param array lines Street Address
     * @return type
     */
    protected function buildShippingAddressNode(array $lines)
    {
        return sprintf(
            '<ShippingAddress>%s<City>%s</City>%s<CountryCode>%s</CountryCode>%s</ShippingAddress>',
            implode('', $lines),
            $this->xmlEncode($this->getShipToCity()),
            $this->serializeOptionalXmlEncodedValue('MainDivision', $this->getShipToMainDivision()),
            $this->xmlEncode($this->getShipToCountryCode()),
            $this->serializeOptionalXmlEncodedValue('PostalCode', $this->getShipToPostalCode())
        );
    }

    public function getShipToCity()
    {
        return $this->shipToCity;
    }

    public function setShipToCity($city)
    {
        $this->shipToCity = $this->cleanString($city, 35);
        return $this;
    }

    public function getShipToMainDivision()
    {
        return $this->shipToMainDivision;
    }

    public function setShipToMainDivision($div)
    {
        $this->shipToMainDivision = $this->cleanString($div, 35);
        return $this;
    }

    public function getShipToCountryCode()
    {
        return $this->shipToCountryCode;
    }

    public function setShipToCountryCode($code)
    {
        $cleaned = $this->cleanString($code, 40);
        $this->shipToCountryCode = strlen($cleaned) >= 2 ? $cleaned : null;
        return $this;
    }

    public function getShipToPostalCode()
    {
        return $this->shipToPostalCode;
    }

    public function setShipToPostalCode($code)
    {
        $this->shipToPostalCode = $this->cleanString($code, 15);
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
