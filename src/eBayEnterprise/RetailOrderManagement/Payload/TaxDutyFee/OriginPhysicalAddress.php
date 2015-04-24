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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OriginPhysicalAddress implements IOriginPhysicalAddress
{
    use TPayload, TPhysicalAddress;

    /** @var string */
    protected $originAddressRootNodeName = 'Address';

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
            'city' => 'string(x:City)',
            'countryCode' => 'string(x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'buildingName' => 'x:BuildingName',
            'poBox' => 'x:PoBox',
            'mainDivisionName' => 'x:MainDivision',
            'mainDivision' => 'x:MainDivisionCode',
            'countryName' => 'x:CountryName',
            'postalCode' => 'x:PostalCode',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => '*[starts-with(name(), "Line")]'
            ],
        ];
    }

    public function getOriginAddressNodeName()
    {
        return $this->originAddressRootNodeName;
    }

    public function setOriginAddressNodeName($name)
    {
        $this->originAddressRootNodeName = $name;
        return $this;
    }

    protected function buildPhysicalAddressNodes(array $lines)
    {
        return $this->serializeInnerPhysicalAddressData($lines);
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return $this->getOriginAddressNodeName();
    }

    protected function serializeContents()
    {
        return $this->serializePhysicalAddress();
    }

    protected function getRootNodeName()
    {
        return $this->getOriginAddressNodeName();
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
