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

class TaxDutyFeeQuoteReply implements ITaxDutyFeeQuoteReply
{
    use TTopLevelPayload, TDestinationContainer;

    /** @var ITaxedShipGroupIterable */
    protected $shipGroups;

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
        $this->subpayloadExtractionPaths = [
            'shipGroups' => 'x:Shipping/x:ShipGroups',
            'destinations' => 'x:Shipping/x:Destinations',
        ];
    }

    public function getShipGroups()
    {
        return $this->shipGroups;
    }

    public function setShipGroups(ITaxedShipGroupIterable $shipGroups)
    {
        $this->shipGroups = $shipGroups;
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

    protected function getXmlNameSpace()
    {
        return static::XML_NS;
    }

    /**
     * get the root element name
     * @return string
     */
    protected function getRootNodeName()
    {
        return 'TaxDutyQuoteResponse';
    }

    protected function serializeContents()
    {
        $contents = $this->serializeShipping();
        return $contents;
    }

    protected function serializeShipping()
    {
        return '<Shipping>'
            . $this->getShipGroups()->serialize()
            . $this->getDestinations()->serialize()
            . '</Shipping>';
    }
}
