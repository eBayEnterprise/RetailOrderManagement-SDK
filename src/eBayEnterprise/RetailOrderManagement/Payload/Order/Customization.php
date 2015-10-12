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
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Customization implements ICustomization
{
    use TPayload, TCustomizationInstructionContainer;

    const ROOT_NODE = 'Customization';

    /** @var int */
    protected $customizationId;
    /** @var IPriceGroup */
    protected $extendedPrice;
    /** @var string */
    protected $itemId;
    /** @var string */
    protected $customizedItemId;
    /** @var IOrderItem */
    protected $customizedItem;

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->extractionPaths = [
            'itemId' => 'string(x:ItemId)',
        ];
        $this->optionalExtractionPaths = [
            'customizationId' => 'x:CustomizationId',
            'customizedItemId' => 'x:Item/@ref',
        ];
        $this->subpayloadExtractionPaths = [
            'customizationInstructions' => 'x:Instructions',
        ];

        $this->customizationInstructions = $this->buildPayloadForInterface(
            self::CUSTOMIZATION_INSTRUCTION_ITERABLE_INTERFACE
        );
    }

    public function getEmptyExtendedPrice()
    {
        return $this->buildPayloadForInterface(self::PRICE_GROUP_INTERFACE);
    }

    public function getCustomizationId()
    {
        return $this->customizationId;
    }

    public function setCustomizationId($customizationId)
    {
        $this->customizationId = $customizationId;
        return $this;
    }

    public function getExtendedPrice()
    {
        return $this->extendedPrice;
    }

    public function setExtendedPrice(IPriceGroup $extendedPrice)
    {
        $this->extendedPrice = $extendedPrice;
        return $this;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $this->cleanString($itemId, 20);
        return $this;
    }

    public function getCustomizedItem()
    {
        if (!$this->customizedItem) {
            $this->customizedItem = $this->findCustomizedItem();
        }
        return $this->customizedItem;
    }

    public function setCustomizedItem(IOrderItem $customizedItem)
    {
        $this->customizedItem = $customizedItem;
        $orderItemContainer = $this->getOrderItemContainer();
        if ($orderItemContainer) {
            $orderItemContainer->getOrderItems()->offsetSet($customizedItem);
        }
        return $this;
    }

    public function getCustomizedItemId()
    {
        $item = $this->getCustomizedItem();
        return $item ? $item->getId() : $this->customizedItemId;
    }

    protected function getParentOrderItem()
    {
        return $this->getAncestorPayloadOfType('\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItem');
    }

    protected function getOrderItemContainer()
    {
        return $this->getAncestorPayloadOfType('\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemContainer');
    }

    /**
     * Search through related payloads for the item the customization applies
     * to. Will attempt to dereference  an item id if one exists. If there is
     * not an id to dereference, will default to the first order item ancestor.
     * If no suitable item can be found, will return null.
     *
     * This will perform a fresh search on every invocation. As payloads related
     * to this one may mutate around this payload, it is not guaranteed to be
     * idempotent so results are not cached.
     *
     * @return IOrderItem|null
     */
    protected function findCustomizedItem()
    {
        // Get the parent item if there is one, may be null if there isn't.
        $parentItem = $this->getParentOrderItem();
        // If there isn't an id to dereference, accept the parent, whether
        // there is one or not, as the item being referenced. If there is
        // an id to dereference and it matches the parent, no need to search
        // further, so it can be returned.
        if (is_null($this->customizedItemId) ||
            ($parentItem && $parentItem->getId() === $this->customizedItemId)
        ) {
            return $parentItem;
        }

        // If an item hasn't been found yet, look though all items that could
        // be referenced (items in a related IOrderItemContainer) by the item id
        // for a match - know there is an id as if there weren't, the parent
        // item would have already been returned.
        $itemContainer = $this->getOrderItemContainer();
        if ($itemContainer) {
            foreach ($itemContainer->getOrderItems() as $orderItem) {
                if ($orderItem->getId() === $this->customizedItemId) {
                    $this->customizedItem = $orderItem;
                    break;
                }
            }
        }
        // If still no match, one can't be found/doesn't exist so return null.
        return null;
    }

    protected function serializeContents()
    {
        return $this->serializeOptionalXmlEncodedValue('CustomizationId', $this->getCustomizationId())
            . $this->getCustomizationInstructions()->serialize()
            . (!is_null($this->getExtendedPrice()) ? $this->getExtendedPrice()->setRootNodeName('ExtendedPrice')->serialize() : '')
            . "<ItemId>{$this->xmlEncode($this->getItemId())}</ItemId>"
            . $this->serializeCustomizedItem();
    }

    /**
     * When a customized item id has been provided, return a serialization
     * of the reference - "Item" node with "ref" attribute set to the item id.
     * When no customized item id is set, returns an empty string.
     *
     * @return string
     */
    protected function serializeCustomizedItem()
    {
        $itemId = $this->getCustomizedItemId();
        return $itemId ? "<Item ref='{$this->xmlEncode($itemId)}'/>" : '';
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $priceNode = $xpath->query('x:ExtendedPrice')->item(0);
        if ($priceNode) {
            $this->extendedPrice = $this->getEmptyExtendedPrice()->deserialize($priceNode->C14N());
        }
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
