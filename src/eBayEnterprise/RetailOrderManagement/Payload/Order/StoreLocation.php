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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class StoreLocation implements IStoreLocation
{
    use TPayload, TIdentity, TPhysicalAddress;

    const ROOT_NODE = 'StoreLocation';
    const PHISICAL_ADDRESS_ROOT_NODE = 'Address';

    /** @var string */
    protected $storeCode;
    /** @var string */
    protected $storeName;
    /** @var string */
    protected $emailAddress;
    /** @var string */
    protected $phone;

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
            'id' => 'string(@id)',
            'city' => 'string(x:Address/x:City)',
            'countryCode' => 'string(x:Address/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'storeCode' => 'x:StoreCode',
            'storeName' => 'x:StoreName',
            'emailAddress' => 'x:StoreEmail',
            'phone' => 'x:Phone',
            'mainDivision' => 'x:Address/x:MainDivision',
            'postalCode' => 'x:Address/x:PostalCode',
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
        $this->storeCode = $this->cleanString($storeCode, 40);
        return $this;
    }

    public function getStoreName()
    {
        return $this->storeName;
    }

    public function setStoreName($storeName)
    {
        $this->storeName = $this->cleanString($storeName, 100);
        return $this;
    }
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $this->cleanString($emailAddress, 70);
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializeOptionalXmlEncodedValue('StoreCode', $this->getStoreCode())
            . $this->serializeOptionalXmlEncodedValue('StoreName', $this->getStoreName())
            . $this->serializeOptionalXmlEncodedValue('StoreEmail', $this->getEmailAddress())
            . $this->serializePhysicalAddress()
            . $this->serializeOptionalXmlEncodedValue('Phone', $this->getPhone());
    }

    protected function getRootAttributes()
    {
        return ['id' => $this->getId()];
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return static::PHISICAL_ADDRESS_ROOT_NODE;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
