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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;

/**
 * Payload used to fetch a fulfillment delivery estimate and ship from address
 * for one or more line items based on the ship-to address and shipping
 * method on each line item.
 */
class InventoryDetailsRequest implements IInventoryDetailsRequest
{
    use TTopLevelPayload;

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

        $this->items = $this->buildPayloadForInterface(
            static::ITERABLE_INTERFACE
        );
    }

    /**
     * Collection of items.
     *
     * @return IItemIterable
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param IItemIterable
     * @return self
     */
    public function setItems(IItemIterable $items)
    {
        $this->items = $items;
        return $this;
    }

    protected function deserializeExtra($serializedPayload)
    {
        $items = $this->getItems();
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        foreach ($xpath->query('x:OrderItem/x:ShipmentDetails/..') as $itemNode) {
            $item = $this->items->getEmptyShippingItem();
            $item->deserialize($itemNode->C14N());
            $items->attach($item);
        }
        foreach ($xpath->query('x:OrderItem/x:InStorePickupDetails/..') as $itemNode) {
            $item = $this->items->getEmptyInStorePickUpItem();
            $item->deserialize($itemNode->C14N());
            $items->attach($item);
        }
        return $this;
    }

    protected function serializeContents()
    {
        return $this->getItems()->serialize();
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . static::XSD;
    }
}
