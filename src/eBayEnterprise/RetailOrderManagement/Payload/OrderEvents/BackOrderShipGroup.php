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
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class BackOrderShipGroup implements IBackOrderShipGroup
{
    use TPayload, TOrderItemContainer, TShipGroup;

    /** @var DateTime */
    protected $estimatedShipDate;
    /** @var IEddMessage */
    protected $eddMessage;
    /** @var bool */
    protected $hasEddMessageNode;

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
        $this->payloadFactory = new PayloadFactory();

        $this->orderItems =
            $this->buildPayloadForInterface(static::ORDER_ITEM_ITERABLE_INTERFACE);
        $this->eddMessage =
            $this->buildPayloadForInterface(static::EDD_MESSAGE_INTERFACE);

        $this->extractionPaths = [
            'shipmentMethod' => 'string(x:ShipmentMethod)',
        ];
        $this->optionalExtractionPaths = [
            'shipmentMethodDisplayText' => 'x:ShipmentMethod/@displayText',
            'estimatedShipDate' => 'x:EstimatedShipDate'
        ];
        $this->subpayloadExtractionPaths = [
            'eddMessage' => 'x:EDDMessage',
            'orderItems' => 'x:BackorderOrderItems',
        ];
        $this->hasEddMessageNode = false;
    }

    protected function deserializeExtra($serializedPayload)
    {
        $this->setEstimatedShipDate($this->estimatedShipDate);
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $this->hasEddMessageNode = $this->hasEddMessage($xpath);
        return $this->deserializeShippingDestination($xpath);
    }

    protected function serializeContents()
    {
        return $this->getOrderItems()->serialize()
            . $this->serializeShipmentMethod()
            . $this->serializeEstimatedShipDate()
            . $this->serializeEddMessage()
            . $this->getShippingDestination()->serialize();
    }

    /**
     * Checks if the payload xml has the EDDMessage xml node.
     * @param DOMXPath
     * @return bool true the 'EDDMessage' node exits in the xml payload otherwise false
     */
    protected function hasEddMessage(DOMXPath $xpath)
    {
        return ($xpath->query('x:EDDMessage')->length > 0);
    }

    protected function serializeEddMessage()
    {
        return $this->hasEddMessageNode ? $this->getEddMessage()->serialize() : '';
    }

    protected function serializeEstimatedShipDate()
    {
        $date = $this->getEstimatedShipDate();
        return ($date instanceof DateTime)
            ? "<EstimatedShipDate>{$date->format('Y-m-d')}</EstimatedShipDate>" : '';
    }

    public function getEstimatedShipDate()
    {
        return $this->estimatedShipDate;
    }

    public function setEstimatedShipDate($date)
    {
        $this->estimatedShipDate =
            (!empty($date) && is_string($date)) ? new DateTime($date) : null;
        return $this;
    }

    public function getEddMessage()
    {
        return $this->eddMessage;
    }

    public function setEddMessage(IEddMessage $message)
    {
        $this->eddMessage = $message;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [];
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
