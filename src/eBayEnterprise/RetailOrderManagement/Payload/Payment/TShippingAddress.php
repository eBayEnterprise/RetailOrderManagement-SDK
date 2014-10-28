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
    /** @var string **/
    protected $shipToLines;
    /** @var string **/
    protected $shipToCity;
    /** @var string **/
    protected $shipToMainDivision;
    /** @var string **/
    protected $shipToCountryCode;
    /** @var string **/
    protected $shipToPostalCode;
    /**
     * The street address and/or suite and building
     *
     * Newline-delimited string, at most four lines
     * xsd restriction: 1-70 characters per line
     * @return string
     */
    public function getShipToLines()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->shipToLines;
    }
    /**
     * @param string $lines
     * @return self
     */
    public function setShipToLines($lines)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $this->shipToLines = $this->cleanAddressLines($lines);
        return $this;
    }
    /**
     * Name of the city
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    public function getShipToCity()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->shipToCity;
    }
    /**
     * @param string $city
     * @return self
     */
    public function setShipToCity($city)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($city), 0, 40);
        $this->shipToCity = (strlen($cleaned)<2) ? null : $cleaned;
        return $this;
    }
    /**
     * Typically a two- or three-digit postal abbreviation for the state or province.
     * ISO 3166-2 code is recommended, but not required
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    public function getShipToMainDivision()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->shipToMainDivision;
    }
    /**
     * @param string $div
     * @return self
     */
    public function setShipToMainDivision($mainDivision)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($mainDivision), 0, 35);
        $this->shipToMainDivision = (strlen($cleaned)<2) ? null : $cleaned;
        return $this;
    }
    /**
     * Two character country code.
     *
     * xsd restriction: 2-40 characters
     * @return string
     */
    public function getShipToCountryCode()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->shipToCountryCode;
    }
    /**
     * @param string $code
     * @return self
     */
    public function setShipToCountryCode($countryCode)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($countryCode), 0, 40);
        $this->shipToCountryCode = (strlen($cleaned)<2) ? null : $cleaned;
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
    public function getShipToPostalCode()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        return $this->shipToPostalCode;
    }
    /**
     * @param string $code
     * @return self
     */
    public function setShipToPostalCode($postalCode)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IShippingAddress
        $cleaned = substr(trim($postalCode), 0, 40);
        $this->shipToPostalCode = (strlen($cleaned)<1) ? null : $cleaned;
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
            $addressLines = explode("\n", $lines, 4);
            array_walk( $addressLines, function(&$value) {
                $value = substr(trim(preg_replace('/\s+/', ' ', $value)), 0, 70);
            });
            return $addressLines;
        }
        return null;
    }
    /**
     * Serialize a Shipping Address
     * @return string
     */
    protected function serializeShippingAddress($addressStatus = null)
    {
        $lines = 0;
        $node = '<ShippingAddress>';
        if (is_array($this->getShipToLines())) {
            foreach ($this->getShipToLines() as $shipToLine) {
                $lines++;
                $node .= "<Line{$lines}>{$shipToLine}</Line{$lines}>";
            }
        }
        $node .= "<City>{$this->getShipToCity()}</City>"
            . "<MainDivision>{$this->getShipToMainDivision()}</MainDivision>"
            . "<CountryCode>{$this->getShipToCountryCode()}</CountryCode>"
            . "<PostalCode>{$this->getShipToPostalCode()}</PostalCode>";

        if ($addressStatus) {
            $node .= "<AddressStatus>{$addressStatus}</AddressStatus>";
        }

        $node .= "</ShippingAddress>";
        return $node;
    }
}
