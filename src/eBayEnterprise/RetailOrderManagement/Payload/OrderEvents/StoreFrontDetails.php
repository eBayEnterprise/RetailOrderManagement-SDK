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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
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

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        IPayload $parentPayload = null
    ) {
        $this->validators = $validators;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'city' => 'string(x:Address/x:City)',
            'countryCode' => 'string(x:Address/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'x:Address/x:MainDivision',
            'postalCode' => 'x:Address/x:PostalCode',
            'storeCode' => 'x:StoreCode',
            'storeName' => 'x:StoreName',
            'emailAddress' => 'x:StoreEmail',
            'directions' => 'x:StoreDirections',
            'hours' => 'x:StoreHours',
            'phoneNumber' => 'x:StoreFrontPhoneNumber',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:Address/*[starts-with(name(), "Line")]'
            ],
        ];
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
        return $this->serializeOptionalValue('StoreCode', $this->getStoreCode())
            . $this->serializeOptionalValue('StoreName', $this->getStoreName())
            . $this->serializeOptionalValue('StoreEmail', $this->getEmailAddress())
            . $this->serializePhysicalAddress();
    }

    protected function serializeDetails()
    {
        return $this->serializeOptionalValue('StoreDirections', $this->getDirections())
            . $this->serializeOptionalValue('StoreHours', $this->getHours())
            . $this->serializeOptionalValue('StoreFrontPhoneNumber', $this->getPhoneNumber());
    }

    /**
     * Serialize the value as an xml element with the given node name. When
     * given an empty value, returns an empty string instead of an empty
     * element.
     *
     * @param string
     * @param mixed
     * @return string
     */
    protected function serializeOptionalValue($nodeName, $value)
    {
        return $value ? sprintf('<%s>%s</%1$s>', $nodeName, $value) : '';
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
