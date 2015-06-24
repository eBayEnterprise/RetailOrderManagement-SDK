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

class ProxyPickupDetails implements IProxyPickupDetails
{
    use TPayload, TPhysicalAddress;

    const ROOT_NODE = 'ProxyPickupDetails';
    const PHYSICAL_ADDRESS_ROOT_NODE = 'Address';

    /** @var string */
    protected $fullName;
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $lastName;
    /** @var string */
    protected $email;
    /** @var string */
    protected $phone;
    /** @var string */
    protected $relationship;

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
        $this->schemaValidator = $schemaValidator;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'fullName' => 'string(x:PersonName)',
            'firstName' => 'string(x:FirstName)',
            'lastName' => 'string(x:LastName)',
        ];
        $this->optionalExtractionPaths = [
            'email' => 'x:Email',
            'phone' => 'x:Phone',
            'city' => 'x:Address/x:City',
            'mainDivision' => 'x:Address/x:MainDivision',
            'countryCode' => 'x:Address/x:CountryCode',
            'postalCode' => 'x:Address/x:PostalCode',
            'relationship' => 'x:Relationship',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:Address/*[starts-with(name(), "Line")]'
            ],
        ];
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
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

    public function getRelationship()
    {
        return $this->relationship;
    }

    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializeOptionalXmlEncodedValue('PersonName', $this->getFullName())
            . $this->serializeOptionalXmlEncodedValue('FirstName', $this->getFirstName())
            . $this->serializeOptionalXmlEncodedValue('LastName', $this->getLastName())
            . $this->serializeOptionalXmlEncodedValue('Email', $this->getEmail())
            . $this->serializeOptionalXmlEncodedValue('Phone', $this->getPhone())
            . $this->serializePhysicalAddress()
            . $this->serializeOptionalXmlEncodedValue('Relationship', $this->getRelationship());
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return static::PHYSICAL_ADDRESS_ROOT_NODE;
    }
}
