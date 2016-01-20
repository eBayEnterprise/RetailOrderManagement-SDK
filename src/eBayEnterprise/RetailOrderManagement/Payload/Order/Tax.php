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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TPayloadLogger;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Tax implements ITax
{
    use TPayload, TPayloadLogger, TAmount;

    const ROOT_NODE = 'Tax';

    /** @var string */
    protected $type;
    /** @var string */
    protected $taxability;
    /** @var string */
    protected $situs;
    /** @var string */
    protected $jurisdiction;
    /** @var string */
    protected $jurisdictionLevel;
    /** @var string */
    protected $jurisdictionId;
    /** @var string */
    protected $imposition;
    /** @var string */
    protected $impositionType;
    /** @var float */
    protected $effectiveRate;
    /** @var float */
    protected $taxableAmount;
    /** @var float */
    protected $calculatedTax;
    /** @var string */
    protected $sellerRegistrationId;
    /** @var array */
    protected $allowedTaxTypes = [
        self::TAX_TYPE_SALES,
        self::TAX_TYPE_SELLER_USE,
        self::TAX_TYPE_CONSUMER_USE,
        self::TAX_TYPE_VAT,
        self::TAX_TYPE_IMPORT_VAT,
        self::TAX_TYPE_NONE,
    ];
    /** @var array */
    protected $allowedTaxabilities = [
        self::TAXABILITY_TAXABLE,
        self::TAXABILITY_NONTAXABLE,
        self::TAXABILITY_EXEMPT,
        self::TAXABILITY_DPPAPPLIED,
        self::TAXABILITY_NO_TAX,
        self::TAXABILITY_DEFERRED,
    ];
    /** @var array */
    protected $allowedSituses = [
        self::SITUS_ADMINISTRATIVE_DESTINATION,
        self::SITUS_ADMINISTRATIVE_ORIGIN,
        self::SITUS_DESTINATION,
        self::SITUS_PHYSICAL_ORIGIN,
    ];
    /** @var array */
    protected $allowedJurisdictionLevels = [
        self::JURISDICTION_LEVEL_APO,
        self::JURISDICTION_LEVEL_BOROUGH,
        self::JURISDICTION_LEVEL_CITY,
        self::JURISDICTION_LEVEL_COUNTRY,
        self::JURISDICTION_LEVEL_COUNTY,
        self::JURISDICTION_LEVEL_DISTRICT,
        self::JURISDICTION_LEVEL_FPO,
        self::JURISDICTION_LEVEL_LOCAL_IMPROVEMENT_DISTRICT,
        self::JURISDICTION_LEVEL_PARISH,
        self::JURISDICTION_LEVEL_SPECIAL_PURPOSE_DISTRICT,
        self::JURISDICTION_LEVEL_STATE,
        self::JURISDICTION_LEVEL_TERRITORY,
        self::JURISDICTION_LEVEL_TOWNSHIP,
        self::JURISDICTION_LEVEL_TRADE_BLOCK,
        self::JURISDICTION_LEVEL_TRANSIT_DISTRICT,
        self::JURISDICTION_LEVEL_PROVINCE,
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
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'type' => 'string(@taxType)',
            'taxability' => 'string(@taxability)',
            'situs' => 'string(x:Situs)',
            'effectiveRate' => 'number(x:EffectiveRate)',
            'calculatedTax' => 'number(x:CalculatedTax)',
        ];
        $this->optionalExtractionPaths = [
            'jurisdiction' => 'x:Jurisdiction',
            'jurisdictionLevel' => 'x:Jurisdiction/@jurisdictionLevel',
            'jurisdictionId' => 'x:Jurisdiction/@jurisdictionId',
            'imposition' => 'x:Imposition',
            'impositionType' => 'x:Imposition/@impositionType',
            'taxableAmount' => 'x:TaxableAmount',
            'sellerRegistrationId' => 'x:SellerRegistrationId',
        ];
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = in_array($type, $this->allowedTaxTypes) ? $type : null;
        return $this;
    }

    public function getTaxability()
    {
        return $this->taxability;
    }

    public function setTaxability($taxability)
    {
        $this->taxability = in_array($taxability, $this->allowedTaxabilities) ? $taxability : null;
        return $this;
    }

    public function getSitus()
    {
        return $this->situs;
    }

    public function setSitus($situs)
    {
        $this->situs = in_array($situs, $this->allowedSituses) ? $situs : null;
        return $this;
    }

    public function getJurisdiction()
    {
        return $this->jurisdiction;
    }

    public function setJurisdiction($jurisdiction)
    {
        $this->jurisdiction = $jurisdiction;
        return $this;
    }

    public function getJurisdictionLevel()
    {
        return $this->jurisdictionLevel;
    }

    public function setJurisdictionLevel($jurisdictionLevel)
    {
        $isAllowed = in_array($jurisdictionLevel, $this->allowedJurisdictionLevels);
        $this->jurisdictionLevel = $isAllowed ? $jurisdictionLevel : null;
        if (!$isAllowed) {
            $logData = ['jurisdiction_level' => $jurisdictionLevel];
            $this->logger->warning(
                'Jurisdiction Level "{jurisdiction_level}" is not allowed.',
                $this->getLogContextData(__CLASS__, $logData)
            );
        }
        return $this;
    }
    public function getJurisdictionId()
    {
        return $this->jurisdictionId;
    }

    public function setJurisdictionId($jurisdictionId)
    {
        $this->jurisdictionId = $jurisdictionId;
        return $this;
    }

    public function getImposition()
    {
        return $this->imposition;
    }

    public function setImposition($imposition)
    {
        $this->imposition = $imposition;
        return $this;
    }

    public function getImpositionType()
    {
        return $this->impositionType;
    }

    public function setImpositionType($impositionType)
    {
        $this->impositionType = $this->cleanString($impositionType, 60);
        return $this;
    }

    public function getEffectiveRate()
    {
        return $this->effectiveRate;
    }

    public function setEffectiveRate($effectiveRate)
    {
        $this->effectiveRate = $effectiveRate;
        return $this;
    }

    public function getTaxableAmount()
    {
        return $this->taxableAmount;
    }

    public function setTaxableAmount($taxableAmount)
    {
        $this->taxableAmount = $this->sanitizeAmount($taxableAmount);
        return $this;
    }

    public function getCalculatedTax()
    {
        return $this->calculatedTax;
    }

    public function setCalculatedTax($calculatedTax)
    {
        $this->calculatedTax = $this->sanitizeAmount($calculatedTax);
        return $this;
    }

    public function getSellerRegistrationId()
    {
        return $this->sellerRegistrationId;
    }

    public function setSellerRegistrationId($sellerRegistrationId)
    {
        $this->sellerRegistrationId = $sellerRegistrationId;
        return $this;
    }

    protected function serializeContents()
    {
        return "<Situs>{$this->xmlEncode($this->getSitus())}</Situs>"
            . $this->serializeJurisdiction()
            . $this->serializeImposition()
            . $this->serializeEffectiveRate()
            . $this->serializeOptionalAmount('TaxableAmount', $this->getTaxableAmount())
            . $this->serializeAmount('CalculatedTax', $this->getCalculatedTax())
            . $this->serializeOptionalXmlEncodedValue('SellerRegistrationId', $this->getSellerRegistrationId());
    }

    /**
     * Serialize the EffectiveRate.
     * Despite the xsd documentation, this value should not be rounded.
     *
     * @return string
     */
    protected function serializeEffectiveRate()
    {
        // As a side effect, sprintf will prevent any xml-unsafe value if getEffectiveRate is a string.
        return sprintf('<EffectiveRate>%F</EffectiveRate>', $this->getEffectiveRate());
    }

    /**
     * Serialize the tax jurisdiction, id and level.
     *
     * @return string
     */
    protected function serializeJurisdiction()
    {
        $jurisdiction = $this->getJurisdiction();
        return !is_null($jurisdiction)
            ? '<Jurisdiction'
                . $this->serializeOptionalAttribute('jurisdictionId', $this->xmlEncode($this->getJurisdictionId()))
                . $this->serializeOptionalAttribute('jurisdictionLevel', $this->xmlEncode($this->getJurisdictionLevel()))
                . ">{$this->xmlEncode($jurisdiction)}</Jurisdiction>"
            : '';
    }

    /**
     * Serialize the tax imposition and type.
     *
     * @return string
     */
    protected function serializeImposition()
    {
        $imposition = $this->getImposition();
        return !is_null($imposition)
            ? '<Imposition'
                . $this->serializeOptionalAttribute('impositionType', $this->xmlEncode($this->getImpositionType()))
                . ">{$this->xmlEncode($imposition)}</Imposition>"
            : '';
    }

    protected function getRootAttributes()
    {
        return [
            'taxType' => $this->getType(),
            'taxability' => $this->getTaxability(),
        ];
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
