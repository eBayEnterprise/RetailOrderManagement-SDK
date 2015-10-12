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
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TDestinationTarget;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ShipGroup implements IShipGroup
{
    use TPayload, TIdentity, TOrderItemReferenceContainer, TGifting;

    const ROOT_NODE = 'ShipGroup';

    /** @var string */
    protected $chargeType;
    /** @var string */
    protected $destinationId;
    /** @var IDestination */
    protected $destination;

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
            'includeGiftWrapping' => 'boolean(x:Gifting/x:GiftCard)',
        ];
        $this->optionalExtractionPaths = [
            'giftItemId' => 'x:Gifting/x:Gift/x:ItemId',
            'giftMessageTo' => 'x:Gifting/x:Gift/x:Message/x:To',
            'giftMessageFrom' => 'x:Gifting/x:Gift/x:Message/x:From',
            'giftMessageContent' => 'x:Gifting/x:Gift/x:Message/x:Message',
            'giftCardTo' => 'x:Gifting/x:GiftCard/x:Message/x:To',
            'giftCardFrom' => 'x:Gifting/x:GiftCard/x:Message/x:From',
            'giftCardMessage' => 'x:Gifting/x:GiftCard/x:Message/x:Message',
            'packslipTo' => 'x:Gifting/x:Packslip/x:Message/x:To',
            'packslipFrom' => 'x:Gifting/x:Packslip/x:Message/x:From',
            'packslipMessage' => 'x:Gifting/x:Packslip/x:Message/x:Message',
            'localizedToLabel' => 'x:Gifting/*/x:Message/x:To/@localizedDisplayText',
            'localizedFromLabel' => 'x:Gifting/*/x:Message/x:From/@localizedDisplayText',
        ];
        $this->subpayloadExtractionPaths = [
            'itemReferences' => 'x:OrderItems',
            'giftPricing' => 'x:Gifting/x:Gift/x:Pricing',
        ];

        $this->itemReferences = $this->buildPayloadForInterface(
            self::ITEM_REFERENCE_ITERABLE_INTERFACE
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

    public function getDestination()
    {
        if (!$this->destination && $this->destinationId) {
            $destinationContainer = $this->getDestinationContainer();
            if ($destinationContainer) {
                foreach ($destinationContainer->getDestinations() as $destination) {
                    if ($destination->getId() === $this->destinationId) {
                        $this->destination = $destination;
                        break;
                    }
                }
            }
        }
        return $this->destination;
    }

    public function setDestination(IDestination $destination)
    {
        $this->destination = $destination;
        $destinationContainer = $this->getDestinationContainer();
        if ($destinationContainer) {
            $destinationContainer->getDestinations()->offsetSet($destination);
        }
        return $this;
    }

    public function getDestinationId()
    {
        $destination = $this->getDestination();
        return $destination ? $destination->getId() : $this->destinationId;
    }

    /**
     * Get the collection of destinations associated to the payload the ship
     * group belongs to.
     *
     * @return IDestinationIterable
     */
    protected function getDestinationContainer()
    {
        return $this->getAncestorPayloadOfType('\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationContainer');
    }

    protected function serializeContents()
    {
        // May have an actual destination object to reference or just the
        // the id - such as when creating the payload outside a larger context of payloads.
        return "<DestinationTarget ref='{$this->xmlEncode($this->getDestinationId())}'/>"
            . $this->getItemReferences()->serialize()
            . $this->serializeGifting();
    }

    protected function deserializeExtra($serializePayload)
    {
        $xpath = $this->getPayloadAsXPath($serializePayload);
        return $this->deserializeGiftPricing($xpath);
    }

    protected function getRootAttributes()
    {
        return [
            'id' => $this->getId(),
            'chargeType' => $this->getChargeType(),
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
