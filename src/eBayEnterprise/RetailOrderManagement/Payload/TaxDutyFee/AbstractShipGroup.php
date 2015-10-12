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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TDestinationTarget;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractShipGroup
{
    use TPayload, TIdentity, TDestinationTarget;

    const ROOT_NODE = 'ShipGroup';
    const DESTINATION_CONTAINER =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IDestinationContainer';

    /** @var string */
    protected $chargeType;

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

        $this->extractionPaths = [
            'id' => 'string(@id)',
            'chargeType' => 'string(@chargeType)',
            'destinationId' => 'string(x:DestinationTarget/@ref)',
        ];
        $this->optionalExtractionPaths = [
            'giftId' => 'x:Gifting/@id',
            'giftItemId' => 'x:Gifting/x:ItemId',
            'giftDescription' => 'x:Gifting/x:ItemDesc',
        ];
        $this->subpayloadExtractionPaths = [
            'orderItems' => 'x:Items',
            'giftPricing' => 'x:Gifting/x:Pricing',
        ];

        $this->orderItems = $this->buildPayloadForInterface(
            static::ORDER_ITEM_ITERABLE_INTERFACE
        );
    }

    public function getChargeType()
    {
        return $this->chargeType;
    }

    public function setChargeType($chargeType)
    {
        $this->chargeType = $chargeType;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function serializeContents()
    {
        // May have an actual destination object to reference or just the
        // the id - such as when creating the payload outside a larger context of payloads.
        return "<DestinationTarget ref='{$this->xmlEncode($this->getDestinationId())}'/>"
            . $this->getItems()->serialize()
            . $this->serializeGifting();
    }

    protected function deserializeExtra($serializePayload)
    {
        $xpath = $this->getPayloadAsXPath($serializePayload);
        return $this->deserializeGiftPricing($xpath);
    }

    protected function getRootAttributes()
    {
        $attrs = ['id' => $this->getId()];
        if ($this->getChargeType()) {
            $attrs['chargeType'] = $this->getChargeType();
        }
        return $attrs;
    }
}
