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

trait TPhysicalAddress
{

    /** @var string **/
    protected $lines;
    /** @var string **/
    protected $city;
    /** @var string **/
    protected $mainDivision;
    /** @var string **/
    protected $countryCode;
    /** @var string **/
    protected $postalCode;
    /** @var array */
    protected $addressExtractionPaths = [
        'setCity' => 'string(City)',
        'setCountryCode' => 'string(CountryCode)',
    ];
    /** @var array */
    protected $optionalAddressExtractionPaths = [
        'setMainDivision' => 'MainDivision',
        'setPostalCode' => 'PostalCode',
    ];

    /**
     * The street address and/or suite and building
     *
     * Newline-delimited string, at most four lines
     * xsd restriction: 1-70 characters per line
     * @return string
     */
    public function getLines()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return  $this->lines;
    }
    /**
     * @param string $lines
     * @return self
     */
    public function setLines($lines)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $this->lines = $this->cleanAddressLines($lines);
        return $this;
    }
    /**
     * Name of the city
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    public function getCity()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->city;
    }
    /**
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($city), 0, 40);
        $this->city = (strlen($cleaned)<2) ? null : $cleaned;
        return $this;
    }
    /**
     * Typically a two- or three-digit postal abbreviation for the state or province.
     * ISO 3166-2 code is recommended, but not required
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    public function getMainDivision()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->mainDivision;
    }
    /**
     * @param string $div
     * @return self
     */
    public function setMainDivision($mainDivision)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($mainDivision), 0, 35);
        $this->mainDivision = (strlen($cleaned)<2) ? null : $cleaned;
        return $this;
    }
    /**
     * Two character country code.
     *
     * xsd restriction: 2-40 characters
     * @return string
     */
    public function getCountryCode()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->countryCode;
    }
    /**
     * @param string $code
     * @return self
     */
    public function setCountryCode($countryCode)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($countryCode), 0, 40);
        $this->countryCode = (strlen($cleaned)<2) ? null : $cleaned;
        return $this;
    }
    /**
     * Typically, the string of letters and/or numbers that more closely
     * specifies the delivery area than just the City component alone,
     * for example, the Zip Code in the U.S.
     *
     * xsd restriction: 1-15 characters
     * @return string
     */
    public function getPostalCode()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->postalCode;
    }
    /**
     * @param string $code
     * @return self
     */
    public function setPostalCode($postalCode)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($postalCode), 0, 40);
        $this->postalCode = (strlen($cleaned)<1) ? null : $cleaned;
        return $this;
    }
    /**
     * Make sure we have a maximum of 4 address lines, each no more than 70 characters. Multiple whitespace is condensed.
     * Anything over 4 lines gets appended onto the 4th element and the result truncated as needed
     * @param string $lines
     * @return array
     */
    protected function cleanAddressLines($lines)
    {
        if (is_string($lines)) {
            $addressLines = array_filter(explode("\n", $lines, 4));
            array_walk($addressLines, function(&$value) {
                $value = substr(trim(preg_replace('/\s+/', ' ', $value)), 0, 70);
            });
            return implode("\n", $addressLines);
        }
        return null;
    }
    /**
     * Serialize the inner fields of an address
     * @return string
     */
    protected function serializeAddressFields()
    {
        $lineCount = 0;
        $xml = '';
        $lines = explode("\n", $this->getLines());
        if (is_array($lines)) {
            foreach ($lines as $line) {
                ++$lineCount;
                $xml .= "<Line{$lineCount}>{$line}</Line{$lineCount}>";
            }
        }
        return $xml
            . "<City>{$this->getCity()}</City>"
            . $this->outputOptional($this->getMainDivision(), "<MainDivision>{$this->getMainDivision()}</MainDivision>")
            . "<CountryCode>{$this->getCountryCode()}</CountryCode>"
            . $this->outputOptional($this->getPostalCode(), "<PostalCode>{$this->getPostalCode()}</PostalCode>");
    }
    /**
     * return $output if $value evaluates to true
     * otherwise return the empty string.
     * @param  mixed  $value
     * @param  string $output
     * @return string
     */
    protected function outputOptional($value, $output)
    {
        return $value ? $output : '';
    }
    /**
     * deserialize an Address string
     * - the xpath object's document must have the address
     *   element as the document root.
     * @param  \DOMXPath $domXPath
     * @return self
     */
    protected function deserializePhysicalAddressFields(\DOMXPath $domXPath)
    {
        $this->addressLinesFromXPath($domXPath);
        foreach ($this->addressExtractionPaths as $propertySetter => $path) {
            $this->$propertySetter($domXPath->evaluate($path));
        }
        foreach ($this->optionalAddressExtractionPaths as $propertySetter => $path) {
            $foundNode = $domXPath->query($path)->item(0);
            if ($foundNode) {
                $this->$propertySetter($foundNode->nodeValue);
            }
        }
    }
    /**
     * There can be many address lines although only one is required
     * Find all of the nodes in the address node that
     * start with 'Line' and add their value to the
     * proper address lines array
     *
     * @param \DOMXPath $domXPath
     */
    protected function addressLinesFromXPath(\DOMXPath $domXPath)
    {
        $lineNodes = $domXPath->query('*[starts-with(name(), "Line")]');
        $lines = '';
        foreach ($lineNodes as $lineNode) {
            $lines .= "{$lineNode->nodeValue}\n";
        }
        $this->setLines($lines);
    }
}
