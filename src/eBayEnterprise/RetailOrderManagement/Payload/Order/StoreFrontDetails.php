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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class StoreFrontDetails implements IStoreFrontDetails
{
    use TPayload, TPhysicalAddress;

    /** @var string */
    protected $id;
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
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'city' => 'string(x:StoreFrontLocation/x:Address/x:City)',
            'countryCode' => 'string(x:StoreFrontLocation/x:Address/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'id' => 'x:StoreFrontLocation/@id',
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
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
        // Does not use const ROOT_NODE as const was defined based upon an
        // alternate serialization with a different root node name.
        return 'StoreFrontDetails';
    }

    protected function serializeContents()
    {
        return $this->serializeLocation() . $this->serializeDetails();
    }

    /**
     * Serialize the store front location.
     *
     * @return string
     */
    protected function serializeLocation()
    {
        return sprintf('<StoreFrontLocation%s>', $this->serializeOptionalAttribute('id', $this->getId()))
            . $this->serializeOptionalXmlEncodedValue('StoreCode', $this->getStoreCode())
            . $this->serializeOptionalXmlEncodedValue('StoreName', $this->getStoreName())
            . $this->serializeOptionalXmlEncodedValue('StoreEmail', $this->getEmailAddress())
            . $this->serializePhysicalAddress()
            . '</StoreFrontLocation>';
    }

    /**
     * Serialize the store front details - directions, hours and phone number.
     *
     * @return string
     */
    protected function serializeDetails()
    {
        return $this->serializeOptionalXmlEncodedValue('StoreDirections', $this->getDirections())
            . $this->serializeOptionalXmlEncodedValue('StoreHours', $this->getHours())
            . $this->serializeOptionalXmlEncodedValue('StoreFrontPhoneNumber', $this->getPhoneNumber());
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
