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

class ItemRelationship implements IItemRelationship
{
    use TPayload, TOrderItemReferenceContainer;

    const ROOT_NODE = 'Relationship';

    /** @var string */
    protected $parentItemId;
    /** @var IOrderItem */
    protected $parentItem;
    /** @var string */
    protected $type;
    /** @var string */
    protected $name;
    /** @var array */
    protected $allowedRelationshipTypes = [
        self::TYPE_BUNDLE,
        self::TYPE_WARRANTY,
        self::TYPE_KIT,
        self::TYPE_DYNAMIC,
        self::TYPE_CUSTOMIZATION,
    ];

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
            'parentItemId' => 'string(@parent)',
            'type' => 'string(x:Type)',
        ];
        $this->optionalExtractionPaths = [
            'name' => 'x:Name',
        ];
        $this->subpayloadExtractionPaths = [
            'itemReferences' => 'x:Members',
        ];

        $this->itemReferences = $this->buildPayloadForInterface(
            self::ITEM_REFERENCE_ITERABLE_INTERFACE
        );
    }

    public function getParentItem()
    {
        if (!$this->parentItem && $this->parentItemId) {
            $itemContainer = $this->getItemContainer();
            if ($itemContainer) {
                foreach ($itemContainer->getOrderItems() as $item) {
                    if ($item->getId() === $this->parentItemId) {
                        $this->parentItem = $item;
                        break;
                    }
                }
            }
        }
        return $this->parentItem;
    }

    public function setParentItem(IOrderItem $parentItem)
    {
        $this->parentItem = $parentItem;
        $itemContainer = $this->getItemContainer();
        if ($itemContainer) {
            $itemContainer->getOrderItems()->offsetSet($parentItem);
        }
        return $this;
    }

    public function getParentItemId()
    {
        $item = $this->getParentItem();
        return $item ? $item->getId() : $this->parentItemId;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = in_array($type, $this->allowedRelationshipTypes) ? $type : null;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get the order item container associated with this payload. Will return
     * null if one cannot be found.
     *
     * @return IOrderItemContainer|null
     */
    protected function getItemContainer()
    {
        $this->debug = true;
        return $this->getAncestorPayloadOfType('\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemContainer');
    }

    protected function serializeContents()
    {
        return $this->getItemReferences()->setRootNodeName('Members')->serialize()
            . "<Type>{$this->xmlEncode($this->getType())}</Type>"
            . $this->serializeOptionalXmlEncodedValue('Name', $this->getName());
    }

    protected function getRootAttributes()
    {
        return ['parent' => $this->getParentItemId()];
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
