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

namespace eBayEnterprise\RetailOrderManagement\AddressValidation;
use ArrayAccess;
use RuntimeException;
use SplFixedArray;

class Address
{
    /** @var SplFixedArray $lines The street address, suite and building identifiers for the physical address */
    protected $lines;
    protected $lineFmt = '%.70s';
    /** @var string $city Name of the city */
    protected $city;
    protected $cityFmt = '%.35s';
    /**
     * @var string $mainDivision Typically a two- or three-digit postal abbreviation for the state or province
     * Use of the ISO 3166-2 code is recommended, but not required.
     */
    protected $mainDivision;
    protected $mainDivisionFmt = '%.35s';
    /**
     * @var string $countryCode Two digit country code
     * Use of ISO 3166 alpha 2 code is recommended, but not required.
     */
    protected $countryCode;
    protected $countryCodeFmt = '%.40s';
    /** @var string $postalCode for example, the Zip Code in the U.S. */
    protected $postalCode;
    protected $postalCodeFmt = '%.15s';

    /**
     * @param ArrayAccess $linesContainer Optional container for address lines
     * @param string $lines
     * @param string $city
     * @param string $mainDivision
     * @param string $countryCode
     * @param string $postalCode
     */
    public function __construct(ArrayAccess $linesContainer=null, $lines='', $city='', $mainDivision='', $countryCode='', $postalCode='')
    {
        $this->lines = is_null($linesContainer) ? new SplFixedArray(4) : $linesContainer;
        $this
            ->setLines($lines)
            ->setCity($city)
            ->setMainDivision($mainDivision)
            ->setCountryCode($countryCode)
            ->setPostalCode($postalCode);
    }

    /**
     * Format the string. This method is intended for later expansion.
     * @param string $spec The format specification
     * @param string $string The string to format
     * @return string The formatted string
     */
    protected function format($spec, $string)
    {
        return sprintf($spec, $string);
    }

    /**
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->format($this->cityFmt, $this->city);
    }

    /**
     * @param string $countryCode
     * @return self
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->format($this->countryCodeFmt, $this->countryCode);
    }

    /**
     * Set the specified address line to the specified value
     * @param int $number The line number (1-indexed)
     * @param string $val The address line to set
     * @return self
     */
    public function setLine($number, $val)
    {
        try {
            $this->lines[$number - 1] = $val;
        } catch (RuntimeException $e) {
            // Ignore errors caused by setting invalid address lines.
        }
        return $this;
    }

    /**
     * @param int $number The line number to get (1-indexed)
     * @return string
     */
    public function getLine($number)
    {
        try {
            return $this->format($this->lineFmt, $this->lines[$number - 1]);
        } catch (RuntimeException $e) {
            // Ignore errors caused by trying to get invalid address lines.
        }
        return '';
    }

    /**
     * Set all the address lines by splitting a string on a delimiter.
     * @param string $lines The newline-delimited string
     * @param string $delimiter
     * @return self
     */
    public function setLines($lines, $delimiter="\n")
    {
        $linesArray = preg_split($delimiter, $lines, -1, PREG_SPLIT_NO_EMPTY);
        foreach($linesArray as $i => $line) {
            $this->setLine($i + 1, $line);
        }
        return $this;
    }

    /**
     * Get the formatted address lines joined by a delimiter.
     * @param string $delimiter
     * @return string
     */
    public function getLines($delimiter="\n")
    {
        $formattedLines = array();
        foreach(array_filter($this->lines) as $line) {
            $formattedLines[] = $this->format($this->lineFmt, $line);
        }
        return implode($delimiter, array_filter($formattedLines));
    }

    /**
     * @param string $mainDivision
     * @return self
     */
    public function setMainDivision($mainDivision)
    {
        $this->mainDivision = $mainDivision;
        return $this;
    }

    /**
     * @return string
     */
    public function getMainDivision()
    {
        return $this->format($this->mainDivisionFmt, $this->mainDivision);
    }

    /**
     * @param string $postalCode
     * @return self
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode()
    {
        return $this->format($this->postalCodeFmt, $this->postalCode);
    }
}
