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

use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

class StoreFrontDetails implements IStoreFrontDetails
{
    use TPayload, TPhysicalAddress;

    /** @var string */
    protected $storeCode;
    /** @var string */
    protected $storeName;
    /** @var string */
    protected $emailAddress;
    /** @var string */
    protected $directions;
    /** @var string */
    protected $hours;
    /** @var string */
    protected $phoneNumber;
    /** @var string */
    protected $locationId;

    /**
     * @param IValidatorIterator
     */
    public function __construct(IValidatorIterator $validators)
    {
        $this->extractionPaths = [
            'city' => 'string(x:StoreFrontLocation/x:Address/x:City)',
            'countryCode' => 'string(x:StoreFrontLocation/x:Address/x:CountryCode)',
            'locationId' => 'string(x:StoreFrontLocation/@id)'
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'x:StoreFrontLocation/x:Address/x:MainDivision',
            'postalCode' => 'x:StoreFrontLocation/x:Address/x:PostalCode',
            'storeCode' => 'x:StoreFrontLocation/x:StoreCode',
            'storeName' => 'x:StoreFrontLocation/x:StoreName',
            'emailAddress' => 'x:StoreFrontLocation/x:StoreEmail',
            'directions' => 'x:StoreDirections',
            'hours' => 'x:StoreHours',
            'phoneNumber' => 'x:StoreFrontPhoneNumber',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:StoreFrontLocation/x:Address/*[starts-with(name(), "Line")]'
            ],
        ];
        $this->validators = $validators;
    }

    public function getStoreCode()
    {
        return $this->storeCode;
    }

    public function setStoreCode($storeCode)
    {
        $this->storeCode = $this->cleanString($storeCode, static::STORE_CODE_MAX_LENGTH);
        return $this;
    }

    public function getStoreName()
    {
        return $this->storeName;
    }

    public function setStoreName($storeName)
    {
        $this->storeName = $this->cleanString($storeName, static::STORE_NAME_MAX_LENGTH);
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $this->cleanString($emailAddress, static::EMAIL_ADDRESS_MAX_LENGTH);
        return $this;
    }

    public function getDirections()
    {
        return $this->directions;
    }

    public function setDirections($directions)
    {
        $this->directions = $directions;
        return $this;
    }

    public function getHours()
    {
        return $this->hours;
    }

    public function setHours($hours)
    {
        $this->hours = $hours;
        return $this;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getLocationId()
    {
        return $this->locationId;
    }

    public function setLocationId($locationId)
    {
        $this->locationId = $this->cleanId($locationId);
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return $this->serializeLocation() . $this->serializeDetails();
    }

    protected function serializeLocation()
    {
        return "<StoreFrontLocation id='{$this->getLocationId()}'>"
            . "<StoreCode>{$this->getStoreCode()}</StoreCode>"
            . "<StoreName>{$this->getStoreName()}</StoreName>"
            . "<StoreEmail>{$this->getEmailAddress()}</StoreEmail>"
            . $this->serializePhysicalAddress()
            . '</StoreFrontLocation>';
    }

    protected function serializeDetails()
    {
        return "<StoreDirections>{$this->getDirections()}</StoreDirections>"
            . "<StoreHours>{$this->getHours()}</StoreHours>"
            . "<StoreFrontPhoneNumber>{$this->getPhoneNumber()}</StoreFrontPhoneNumber>";
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return static::PHYSICAL_ADDRESS_ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
