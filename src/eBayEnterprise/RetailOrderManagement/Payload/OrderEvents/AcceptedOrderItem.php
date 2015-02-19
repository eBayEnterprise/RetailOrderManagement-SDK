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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use DateTime;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class AcceptedOrderItem extends OrderItem implements IAcceptedOrderItem
{
    use TProductPrice, TItemShipping, TAmount {
        TAmount::serializeAmount insteadof TProductPrice;
        TAmount::sanitizeAmount insteadof TProductPrice;
    }

    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        parent::__construct($validators, $schemaValidator, $payloadMap, $logger, $parentPayload);

        $this->logger = $logger;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new PayloadFactory();

        $this->extractionPaths = array_merge(
            $this->extractionPaths,
            [
                'shipmentMethod' => 'string(x:Shipping/x:ShipmentMethod)',
                'shipmentMethodDisplayText' => 'string(x:Shipping/x:ShipmentMethod/@displayText)',
            ]
        );
        $this->optionalExtractionPaths = array_merge(
            $this->optionalExtractionPaths,
            [
                'amount' => 'x:Pricing/x:Amount',
                'unitPrice' => 'x:Pricing/x:UnitPrice',
                'remainder' => 'x:Pricing/x:Amount/@remainder',
            ]
        );
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        return $this->deserializeDestination($xpath)->deserializesShipDate($xpath);
    }

    /**
     * Shipping destination may be one of two payload types. If the message
     * includes a ShippedAddress node, use the mailing address destination type.
     * If the message includes a StoreFrontAddress node, use a store front
     * details destination type.
     *
     * @return self
     */
    protected function deserializeDestination(DOMXPath $xpath)
    {
        $destinationNode = $xpath->query('x:Shipping/x:ShippedAddress|x:Shipping/x:StoreFrontAddress')->item(0);
        if ($destinationNode) {
            $mailingAddress = static::MAILING_ADDRESS_INTERFACE;
            $storeFront = static::STORE_FRONT_DETAILS_INTERFACE;
            $destination = null;
            switch ($destinationNode->nodeName) {
                case $mailingAddress::ROOT_NODE:
                    $destination = $this->buildPayloadForInterface($mailingAddress);
                    break;
                case $storeFront::ROOT_NODE:
                    $destination = $this->buildPayloadForInterface($storeFront);
                    break;
            }
            if ($destination) {
                $destination->deserialize($destinationNode->C14N());
                $this->setDestination($destination);
            }
        }
        return $this;
    }

    /**
     * Deserialize the EstimatedShipDate to a DateTime object.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializesShipDate(DOMXPath $xpath)
    {
        $estimatedShipDate = $xpath->evaluate('string(x:Shipping/x:EstimatedShipDate)');
        if ($estimatedShipDate) {
            $shipDate = new DateTime($estimatedShipDate);
            $this->setEstimatedShipDate($shipDate);
        }
        return $this;
    }

    protected function serializeContents()
    {
        return parent::serializeContents()
            . ($this->hasPricingData() ? $this->serializeProductPrice() : '')
            . ($this->hasShippingData() ? $this->serializeItemShipping() : '');
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    /**
     * Check if the payload has shipping data to include in the serialization.
     *
     * @return bool
     */
    protected function hasShippingData()
    {
        return (bool) $this->getShipmentMethod();
    }

    /**
     * Check if the payload has pricing data to include in the serialization.
     *
     * @return bool
     */
    protected function hasPricingData()
    {
        return $this->getAmount() || $this->getUnitPrice();
    }
}
