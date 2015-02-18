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

namespace eBayEnterprise\RetailOrderManagement\Payload\Address;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;

class SuggestedAddress implements ISuggestedAddress
{
    use TPayload, TResultAddress;

    const ROOT_NODE = 'SuggestedAddress';

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory();

        $this->errorLocations = $this->buildPayloadForInterface(
            static::ERROR_LOCATION_ITERABLE_INTERFACE
        );

        $this->extractionPaths = [
            'city' => 'string(x:City)',
            'countryCode' => 'string(x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'mainDivision' => 'x:MainDivision',
            'postalCode' => 'x:PostalCode',
            'formattedAddress' => 'x:FormattedAddress',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => '*[starts-with(name(), "Line")]'
            ],
        ];
        $this->subpayloadExtractionPaths = [
            'errorLocations' => 'x:ErrorLocations',
        ];
    }

    public function serialize()
    {
        // validate the payload data
        $this->validate();
        $xmlString = $this->serializeContents();
        $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
        return $canonicalXml;
    }

    protected function serializeContents()
    {
        return $this->serializePhysicalAddress();
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return $this->getRootNodeName();
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
