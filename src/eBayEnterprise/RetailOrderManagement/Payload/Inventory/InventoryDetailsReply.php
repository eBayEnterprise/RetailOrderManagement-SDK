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
use \DOMNodeList;

/**
 * Payload used to fetch a fulfillment delivery estimate and ship from address
 * for one or more line items based on the ship-to address and shipping
 * method on each line item.
 */
class InventoryDetailsReply implements IInventoryDetailsReply
{
    use TTopLevelPayload;

    /** @var IItemIterable */
    protected $unavailableItems;
    /** @var IItemIterable */
    protected $detailItems;

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

        $this->detailItems = $this->buildPayloadForInterface(
            static::DETAIL_ITERABLE_INTERFACE
        );
        $this->unavailableItems = $this->buildPayloadForInterface(
            static::UNAVAILABLE_ITERABLE_INTERFACE
        );
    }

    /**
     * Collection of items which cannot be fulfilled.
     *
     * @return IItemIterable
     */
    public function getUnavailableItems()
    {
        return $this->unavailableItems;
    }

    /**
     * @param IItemIterable
     * @return self
     */
    public function setUnavailableItems(IItemIterable $items)
    {
        $this->unavailableItems = $items;
        return $this;
    }

    /**
     * Collection of items which can be fulfiled.
     *
     * @return IItemIterable
     */
    public function getDetailItems()
    {
        return $this->detailItems;
    }

    /**
     * @param IItemIterable
     * @return self
     */
    public function setDetailItems(IItemIterable $items)
    {
        $this->detailItems = $items;
        return $this;
    }

    protected function serializeContents()
    {
        $unavailableItems = $this->getUnavailableItems();
        return "<InventoryDetails>{$this->getDetailItems()->serialize()}</InventoryDetails>"
            . (count($unavailableItems) ? "<UnavailableItems>{$unavailableItems->serialize()}</UnavailableItems>" : '');
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $this->deserializeItems(
            $xpath->query('x:InventoryDetails/x:InventoryDetail'),
            $this->getDetailItems(),
            'getEmptyDetailItem'
        );
        $this->deserializeItems(
            $xpath->query('x:UnavailableItems/x:UnavailableItem'),
            $this->getUnavailableItems(),
            'getEmptyUnavailableItem'
        );
    }

    /**
     * deserialize an IItem and attaches it to the given iterable
     *
     * @param  DOMNodeList
     * @param  IItemIterable
     * @param  string
     * @return self
     */
    protected function deserializeItems(DOMNodeList $itemNodes, IItemIterable $iterable, $subPayloadMethod)
    {
        foreach ($itemNodes as $itemNode) {
            $payload = $iterable->$subPayloadMethod();
            $payload->deserialize($itemNode->C14N());
            $iterable->attach($payload);
        }
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

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . static::XSD;
    }
}
