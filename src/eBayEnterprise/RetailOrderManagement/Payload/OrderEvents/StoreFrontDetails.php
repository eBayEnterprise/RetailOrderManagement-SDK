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
            'city' => 'string(StoreFrontLocation/Address/City)',
            'countryCode' => 'string(StoreFrontLocation/Address/CountryCode)',
            'locationId' => 'string(StoreFrontLocation/@id)'
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'StoreFrontLocation/Address/MainDivision',
            'postalCode' => 'StoreFrontLocation/Address/PostalCode',
            'storeCode' => 'StoreFrontLocation/StoreCode',
            'storeName' => 'StoreFrontLocation/StoreName',
            'emailAddress' => 'StoreFrontLocation/StoreEmail',
            'directions' => 'StoreDirections',
            'hours' => 'StoreHours',
            'phoneNumber' => 'StoreFrontPhoneNumber',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'StoreFrontLocation/Address/*[starts-with(name(), "Line")]'
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
}
