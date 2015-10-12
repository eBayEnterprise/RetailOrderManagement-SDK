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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\Order\TOrderCustomer;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderDetailCustomer implements IOrderDetailCustomer
{
    use TPayload, TOrderCustomer;

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
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = $this->getNewPayloadFactory();

        $this->initExtractPaths()
            ->initOptionalExtractPaths()
            ->initBooleanExtractPaths()
            ->initDatetimeExtractPaths()
            ->initSubPayloadExtractPaths()
            ->initSubPayloadProperties();
    }

    /**
     * Initialize the protected class property array self::extractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initExtractPaths()
    {
        $this->extractionPaths = [
            'firstName' => 'string(x:Name/x:FirstName)',
            'lastName' => 'string(x:Name/x:LastName)',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::optionalExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initOptionalExtractPaths()
    {
        $this->optionalExtractionPaths = [
            'customerId' => '@customerId',
            'gender' => 'x:Gender',
            'emailAddress' => 'x:EmailAddress',
            'taxId' => 'x:CustomerTaxId',
            'middleName' => 'x:Name/x:MiddleName',
            'honorificName' => 'x:Name/x:Honorific',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::booleanExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initBooleanExtractPaths()
    {
        $this->booleanExtractionPaths = [
            'taxExempt' => 'string(x:TaxExemptFlag)',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::datetimeExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initDatetimeExtractPaths()
    {
        $this->datetimeExtractionPaths = [
            'dateOfBirth' => 'string(x:DateOfBirth)',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::subpayloadExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initSubPayloadExtractPaths()
    {
        $this->subpayloadExtractionPaths = [
            'loyaltyPrograms' => 'x:LoyaltyPrograms',
        ];
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setLoyaltyPrograms($this->buildPayloadForInterface(
            static::LOYALTY_PROGRAM_ITERABLE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeOrderDetailCustomer();
    }

    /**
     * Serialize the order detail customer data into a string of XML.
     *
     * @return string
     */
    protected function serializeOrderDetailCustomer()
    {
        return $this->serializePersonName()
            . $this->serializeOptionalXmlEncodedValue('Gender', $this->getGender())
            . $this->serializeOptionalDateValue('DateOfBirth', 'Y-m-d', $this->getDateOfBirth())
            . $this->serializeOptionalXmlEncodedValue('EmailAddress', $this->getEmailAddress())
            . $this->serializeOptionalXmlEncodedValue('CustomerTaxId', $this->getTaxId())
            . $this->serializeTaxExemptFlag()
            . $this->getLoyaltyPrograms()->serialize();
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getPersonNameRootNodeName()
    {
        return self::PERSON_NAME_ROOT_NODE;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $customerId = $this->getCustomerId();
        return $customerId ? ['customerId' => $customerId] : [];
    }
}
