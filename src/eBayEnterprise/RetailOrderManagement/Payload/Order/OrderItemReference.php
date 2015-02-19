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
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderItemReference implements IOrderItemReference
{
    use TPayload;

    const ROOT_NODE = 'Item';

    /** @var string */
    protected $referencedItemId;
    /** @var IOrderItem */
    protected $referencedItem;

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

        $this->extractionPaths = [
            'referencedItemId' => 'string(@ref)',
        ];
    }

    public function getReferencedItem()
    {
        if (!$this->referencedItem && $this->referencedItemId) {
            $itemContainer = $this->getItemContainer();
            if ($itemContainer) {
                foreach ($itemContainer->getOrderItems() as $item) {
                    if ($item->getId() === $this->referencedItemId) {
                        $this->referencedItem = $item;
                        break;
                    }
                }
            }
        }
        return $this->referencedItem;
    }

    public function setReferencedItem(IOrderItem $referencedItem)
    {
        $this->referencedItem = $referencedItem;
        $itemContainer = $this->getItemContainer();
        if ($itemContainer) {
            $itemContainer->getOrderItems()->offsetSet($referencedItem);
        }
        return $this;
    }

    public function getReferencedItemId()
    {
        $item = $this->getReferencedItem();
        return $item ? $item->getId() : $this->referencedItemId;
    }

    /**
     * Get the order item container associated with this payload. Will return
     * null if one cannot be found.
     *
     * @return IOrderItemContainer|null
     */
    protected function getItemContainer()
    {
        return $this->getAncestorPayloadOfType('\eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemContainer');
    }

    protected function serializeContents()
    {
        return '';
    }

    protected function getRootAttributes()
    {
        return [
            'ref' => $this->getReferencedItemId(),
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
