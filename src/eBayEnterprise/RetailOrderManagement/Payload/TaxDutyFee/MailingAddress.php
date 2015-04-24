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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPersonName as TCheckoutPersonName;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class MailingAddress implements IMailingAddress
{
    use TPayload, TIdentity, TPhysicalAddress, TCheckoutPersonName;

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
            'firstName' => 'string(x:PersonName/x:FirstName)',
            'lastName' => 'string(x:PersonName/x:LastName)',
            'city' => 'string(x:Address/x:City)',
            'countryCode' => 'string(x:Address/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'middleName' => 'x:PersonName/x:MiddleName',
            'honorificName' => 'x:PersonName/x:Honorific',
            'buldingName' => 'x:Address/BuildingName',
            'poBox' => 'x:Address/PoBox',
            'mainDivisionName' => 'x:Address/x:MainDivision',
            'mainDivision' => 'x:Address/x:MainDivisionCode',
            'countryName' => 'x:Address/x:CountryName',
            'postalCode' => 'x:Address/x:PostalCode',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:Address/*[starts-with(name(), "Line")]'
            ],
        ];
    }

    protected function serializeContents()
    {
        return $this->serializePersonName() . $this->serializePhysicalAddress();
    }

    protected function getRootNodeName()
    {
        return 'MailingAddress';
    }

    protected function getPersonNameRootNodeName()
    {
        return 'PersonName';
    }

    protected function getRootAttributes()
    {
        return ['id' => $this->getId()];
    }

    protected function buildPhysicalAddressNodes(array $lines)
    {
        $rootNode = $this->getPhysicalAddressRootNodeName();
        return "<$rootNode>" . $this->serializeInnerPhysicalAddressData($lines) . "</$rootNode>";
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return 'Address';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
