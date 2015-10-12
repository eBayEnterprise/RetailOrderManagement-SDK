<?php
/**
 * Copyright (c) 2014-2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2014-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use DateTime;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TDestinationContainer;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TaxDutyFeeQuoteRequest implements ITaxDutyFeeQuoteRequest
{

    use TTopLevelPayload, TShipGroupContainer, TDestinationContainer;

    /** @var string */
    protected $currency;
    /** @var bool */
    protected $vatInclusivePricingFlag;
    /** @var string */
    protected $customerTaxId;
    /** @var string */
    protected $billingInformationIdRef;

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

        $this->shipGroups = $this->buildPayloadForInterface(
            static::SHIP_GROUP_ITERABLE_INTERFACE
        );
        $this->destinations = $this->buildPayloadForInterface(
            static::DESTINATION_ITERABLE_INTERFACE
        );
        $this->extractionPaths = [
            'billingInformationIdRef' => 'string(x:BillingInformation/@ref)',
            'currency' => 'string(x:Currency)',
        ];
        $this->optionalExtractionPaths = [
            'customerTaxId' => 'x:CustomerTaxId',
        ];
        $this->booleanExtractionPaths = [
            'vatInclusivePricingFlag' => 'string(x:VATInclusivePricing)',
        ];
        $this->subpayloadExtractionPaths = [
            'shipGroups' => 'x:Shipping/x:ShipGroups',
            'destinations' => 'x:Shipping/x:Destinations',
        ];
    }

    /**
     * Currency code for the request.
     *
     * restrictions: 2 >= length <= 40
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string
     * @return self
     */
    public function setCurrency($currency)
    {
        $clean = $this->cleanString($currency, 40);
        $this->currency = strlen($clean) > 1 ? $clean : null;
        return $this;
    }

    /**
     * Flag indicating prices already have VAT tax included.
     *
     * restrictions: optional
     * @return bool
     */
    public function getVatInclusivePricingFlag()
    {
        return $this->vatInclusivePricingFlag;
    }

    /**
     * @param bool
     * @return self
     */
    public function setVatInclusivePricingFlag($flag)
    {
        $this->vatInclusivePricingFlag = $flag;
        return $this;
    }

    /**
     * Tax Identifier for the customer.
     *
     * restrictions: optional
     * @return string
     */
    public function getCustomerTaxId()
    {
        return $this->customerTaxId;
    }

    /**
     * @param string
     * @return self
     */
    public function setCustomerTaxId($id)
    {
        $this->customerTaxId = $this->cleanString($id, 40);
        return $this;
    }

    /**
     * Customer billing address
     *
     * @return IDestination
     */
    public function getBillingInformation()
    {
        foreach ($this->getDestinations() as $destination) {
            if ($destination->getId() === $this->billingInformationIdRef) {
                return $destination;
            }
        }
        return isset($destination) ? $destination : null;
    }

    /**
     * @param IDestination
     * @return self
     */
    public function setBillingInformation(IDestination $billingDest)
    {
        $this->billingInformationIdRef = $billingDest->getId();
        $this->destinations->attach($billingDest);
        return $this;
    }

    /**
     * get the schema file name
     * @return string
     */
    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    /**
     * get the root element name
     * @return string
     */
    protected function getRootNodeName()
    {
        return 'TaxDutyQuoteRequest';
    }

    protected function serializeBillingInformation()
    {
        $billingInformation = $this->getBillingInformation();
        if ($billingInformation) {
            return "<BillingInformation ref='{$this->xmlEncode($this->billingInformationIdRef)}'></BillingInformation>";
        }
        return '';
    }

    protected function serializeCurrency()
    {
        return "<Currency>{$this->xmlEncode($this->getCurrency())}</Currency>";
    }

    protected function getXmlNameSpace()
    {
        return static::XML_NS;
    }

    protected function serializeShipping()
    {
        return '<Shipping>'
            . $this->getShipGroups()->serialize()
            . $this->getDestinations()->serialize()
            . '</Shipping>';

    }

    protected function serializeVatInclusivePricing()
    {
        $flag =$this->getVatInclusivePricingFlag();
        if ($flag === true || $flag === false) {
            $flag = $this->convertBooleanToString($flag);
            return "<VATInclusivePricing>$flag</VATInclusivePricing>";
        }
        return '';
    }

    protected function serializeContents()
    {
        $contents = $this->serializeCurrency()
            . $this->serializeVatInclusivePricing()
            . $this->serializeOptionalXmlEncodedValue('CustomerTaxId', $this->getCustomerTaxId())
            . $this->serializeBillingInformation()
            . $this->serializeShipping();
        return $contents;
    }
}
