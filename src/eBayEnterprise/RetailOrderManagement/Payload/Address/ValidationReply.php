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
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;

class ValidationReply implements IValidationReply
{
    use TTopLevelPayload, TValidationHeader, TResultAddress, TSuggestedAddressContainer, TResultErrorLocationContainer;

    const RESULT_ADDRESS_NODE = 'RequestAddress';

    /** @var string */
    protected $resultCode;
    /** @var int */
    protected $resultSuggestionCount;
    /** @var string[] */
    protected $validResultCodes = [self::RESULT_VALID];
    /** @var string[] */
    protected $acceptResultCodes = [
        self::RESULT_VALID,
        self::RESULT_NOT_SUPPORTED,
        self::RESULT_UNABLE_TO_CONTACT_PROVIDER,
        self::RESULT_TIMEOUT,
        self::RESULT_PROVIDER_ERROR,
        self::RESULT_MALFORMED,
    ];

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->errorLocations = $this->buildPayloadForInterface(
            static::ERROR_LOCATION_ITERABLE_INTERFACE
        );
        $this->resultErrorLocations = $this->buildPayloadForInterface(
            static::RESULT_ERROR_LOCATION_ITERABLE_INTERFACE
        );
        $this->suggestedAddresses = $this->buildPayloadForInterface(
            static::SUGGESTED_ADDRESS_ITERABLE_INTERFACE
        );

        $this->extractionPaths = [
            'maxSuggestions' => 'number(x:Header/x:MaxAddressSuggestions)',
            'city' => 'string(x:RequestAddress/x:City)',
            'countryCode' => 'string(x:RequestAddress/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'formattedAddress' => 'x:RequestAddress/x:FormattedAddress',
            'mainDivision' => 'x:RequestAddress/x:MainDivision',
            'postalCode' => 'x:RequestAddress/x:PostalCode',
            'resultCode' => 'x:Result/x:ResultCode',
            'resultSuggestionCount' => 'x:Result/x:ResultSuggestionCount',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:RequestAddress/*[starts-with(name(), "Line")]'
            ],
        ];
        $this->subpayloadExtractionPaths = [
            'errorLocations' => 'x:RequestAddress/x:ErrorLocations',
            'resultErrorLocations' => 'x:Result/x:ErrorLocations',
            'suggestedAddresses' => 'x:Result/x:SuggestedAddresses',
        ];
    }

    /**
     * Result code of the address validation request. Indicates if the address
     * validated successfully and, if not, some indication as to why.
     * Possible values:
     * V: the address was verified - submitted address was correct or the
     *    address was standardized
     * C: data was corrected and there are suggested addresses
     * K: address was checked but could not be definitively corrected. Suggested
     *    addresses that have a higher probability of deliverability may or may
     *    not be returned.
     * N: address could not be verified by the service because the address
     *    verifier does not support the country of the address
     * U: unable to contact provider
     * T: provider timed out
     * P: provider returned a system error, only if a P is returned will the
     *    providerErrorText field be populated.
     * M: the request message was malformed or contained invalid data, such as
     *    a non existent country code
     *
     * restrictions: one of {V|C|K|N|U|T|P|M}
     * @return string
     */
    public function getResultCode()
    {
        return $this->resultCode;
    }

    public function setResultCode($resultCode)
    {
        $this->resultCode = $resultCode;
        return $this;
    }

    public function getResultSuggestionCount()
    {
        return $this->resultSuggestionCount;
    }

    public function setResultSuggestionCount($resultSuggestionCount)
    {
        $this->resultSuggestionCount = (int) $resultSuggestionCount;
        return $this;
    }

    /**
     * Indicates if the address validation was completely valid, either as
     * submitted or could be standardized.
     *
     * @return bool
     */
    public function isValid()
    {
        return in_array($this->getResultCode(), $this->validResultCodes)
            || (!$this->hasSuggestions() && $this->getResultCode() === self::RESULT_CORRECTED_WITH_SUGGESTIONS);
    }

    /**
     * Indicates if there are any suggestions included in the reply.
     *
     * @return bool
     */
    public function hasSuggestions()
    {
        return (bool) $this->getSuggestedAddresses()->count();
    }

    /**
     * Indicates if the address can be accepted - either is valid, was
     * standardized, or could not be validated due to a provider issue (in
     * which case, the address can not be found to be valid or invalid).
     *
     * @return bool
     */
    public function isAcceptable()
    {
        return $this->isValid() || in_array($this->getResultCode(), $this->acceptResultCodes);
    }

    protected function serializeContents()
    {
        return $this->serializeValidationHeader()
            . $this->serializePhysicalAddress()
            . $this->serializeResults();
    }

    protected function serializeResults()
    {
        return '<Result>'
            . $this->serializeOptionalXmlEncodedValue('ResultCode', $this->getResultCode())
            . $this->getResultErrorLocations()->serialize()
            . $this->serializeOptionalValue('ResultSuggestionCount', $this->getResultSuggestionCount())
            . $this->getSuggestedAddresses()->serialize()
            . '</Result>';
    }

    protected function getRootNodeName()
    {
        return self::ROOT_NODE;
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return self::RESULT_ADDRESS_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }
}
