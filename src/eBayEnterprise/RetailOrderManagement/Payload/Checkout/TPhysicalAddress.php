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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

trait TPhysicalAddress
{
    /** @var array */
    protected $lines;
    /** @var string */
    protected $city;
    /** @var string */
    protected $mainDivision;
    /** @var string */
    protected $countryCode;
    /** @var string */
    protected $postalCode;

    public function getLines()
    {
        return is_array($this->lines) ? implode("\n", $this->lines) : null;
    }

    public function setLines($lines)
    {
        $this->lines = $this->cleanAddressLines($lines);
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $this->cleanString($city, 35);
        return $this;
    }

    public function getMainDivision()
    {
        return $this->mainDivision;
    }

    public function setMainDivision($div)
    {
        $this->mainDivision = $this->cleanString($div, 35);
        return $this;
    }

    public function getCountryCode()
    {
        return $this->countryCode;
    }

    public function setCountryCode($code)
    {
        $cleaned = $this->cleanString($code, 40);
        $this->countryCode = strlen($cleaned) >= 2 ? $cleaned : null;
        return $this;
    }

    public function getPostalCode()
    {
        return $this->postalCode;
    }

    public function setPostalCode($code)
    {
        $this->postalCode = $this->cleanString($code, 15);
        return $this;
    }

    /**
     * Aggregate the  address lines into the ShippingAddress node. This is an optional node.
     *
     * @return string
     */
    protected function serializePhysicalAddress()
    {
        $lines = [];
        $idx = 0;
        $addressLines = is_array($this->lines) ? $this->lines : [];
        foreach ($addressLines as $line) {
            $idx++;
            $lines[] = sprintf(
                '<Line%d>%s</Line%1$d>',
                $idx,
                $this->xmlEncode($line)
            );
        }
        // If we don't have any address lines, we treat as having no address at all.
        return $idx ? $this->buildPhysicalAddressNodes($lines) : '';
    }

    /**
     * Build the Shipping Address Node
     *
     * @param array lines Street Address
     * @return string
     */
    protected function buildPhysicalAddressNodes(array $lines)
    {
        return sprintf(
            '<%s>%s<City>%s</City>%s<CountryCode>%s</CountryCode>%s</%1$s>',
            $this->getPhysicalAddressRootNodeName(),
            implode('', $lines),
            $this->xmlEncode($this->getCity()),
            $this->serializeOptionalXmlEncodedValue('MainDivision', $this->getMainDivision()),
            $this->xmlEncode($this->getCountryCode()),
            $this->serializeOptionalXmlEncodedValue('PostalCode', $this->getPostalCode())
        );
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
     * Node name wrapping the physical address elements.
     *
     * @return string
     */
    abstract protected function getPhysicalAddressRootNodeName();

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     * @param  mixed  $value
     * @return string | null
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);
}
