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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress as TCheckoutPhysicalAddress;

trait TPhysicalAddress
{
    use TCheckoutPhysicalAddress;

    /** @var string */
    protected $poBox;
    /** @var string */
    protected $buildingName;
    /** @var string */
    protected $mainDivisionName;
    /** @var string */
    protected $countryName;

    public function getBuildingName()
    {
        return $this->buildingName;
    }

    public function setBuildingName($name)
    {
        return $this->buildingName = $name;
    }

    public function getPoBox()
    {
        return $this->poBox;
    }

    public function setPoBox($poBox)
    {
        $this->poBox = $poBox;
        return $this;
    }

    public function getMainDivisionName()
    {
        return $this->mainDivisionName;
    }

    public function setMainDivisionName($name)
    {
        $this->mainDivisionName = $name;
        return $this;
    }

    public function getCountryName()
    {
        return $this->countryName;
    }

    public function setCountryName($name)
    {
        $this->countryName = $name;
        return $this;
    }

    /**
     * serialize the physical address fields without the container element
     *
     * @param  array
     * @return string
     */
    protected function serializeInnerPhysicalAddressData(array $lines)
    {
        return implode('', $lines)
            . $this->serializeOptionalXmlEncodedValue('BuildingName', $this->getBuildingName())
            . $this->serializeOptionalXmlEncodedValue('PoBox', $this->getPoBox())
            . "<City>{$this->xmlEncode($this->getCity())}</City>"
            . $this->serializeOptionalXmlEncodedValue('MainDivision', $this->getMainDivisionName())
            . $this->serializeOptionalXmlEncodedValue('MainDivisionCode', $this->getMainDivision())
            . $this->serializeOptionalXmlEncodedValue('CountryName', $this->getCountryName())
            . "<CountryCode>{$this->xmlEncode($this->getCountryCode())}</CountryCode>"
            . $this->serializeOptionalXmlEncodedValue('PostalCode', $this->getPostalCode());
    }
}
