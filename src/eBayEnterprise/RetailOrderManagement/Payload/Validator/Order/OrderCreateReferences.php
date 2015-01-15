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

namespace eBayEnterprise\RetailOrderManagement\Payload\Validator\Order;

use ArrayAccess;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationIterable;
use eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IValidator;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderCreateRequest;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItem;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemIterable;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItemReferenceContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IShipGroup;

class OrderCreateReferences implements IValidator
{
    /**
     * Validate object references between payloads within the order create
     * request.
     *
     * @param IPayload
     * @return self
     * @throws InvalidPayload
     */
    public function validate(IPayload $payload)
    {
        return $this->validateShipGroupReferences($payload)
            ->validateCustomizationReferences($payload)
            ->validateItemRelationshipReferences($payload);
    }

    /**
     * Validate ship group references to a destination and order items.
     *
     * @param IOrderCreateRequest
     * @return self
     */
    protected function validateShipGroupReferences(IOrderCreateRequest $payload)
    {
        $shipGroups = $payload->getShipGroups();
        $destinations = $payload->getDestinations();
        $orderItems = $payload->getOrderItems();
        foreach ($shipGroups as $shipGroup) {
            $this->validateShipGroupDestination($shipGroup, $destinations)
                ->validateOrderItemReferences($shipGroup, $orderItems);
        }
        return $this;
    }

    /**
     * Validate that a ship group destination points to a destination in the
     * set of known destinations.
     *
     * @param IShipGroup
     * @param IDestinationIterable
     * @return self
     */
    protected function validateShipGroupDestination(IShipGroup $shipGroup, IDestinationIterable $destinations)
    {
        $shipDest = $shipGroup->getDestination();
        return $this->validatePayloadReference($shipGroup, 'destination', $destinations, $shipDest);
    }

    /**
     * Validate that a ship group's items reference items in the known set of
     * order items.
     *
     * @param IShipGroup
     * @param IOrderItemIterable
     * @return self
     */
    protected function validateShipGroupItemReferences(IShipGroup $shipGroup, IOrderItemIterable $orderItems)
    {
        foreach ($shipGroup->getItemReferences() as $itemReference) {
            $item = $itemReference->getReferencedItem();
            $this->validatePayloadReference($itemReference, 'ship group referenced item', $orderItems, $item);
        }
        return $this;
    }

    /**
     * Validate the order item references within the collection of customizations.
     *
     * @param IOrderCreateRequest
     * @return self
     */
    protected function validateCustomizationReferences(IOrderCreateRequest $payload)
    {
        $orderItems = $payload->getOrderItems();
        foreach ($orderItems as $orderItem) {
            $this->validateOrderItemCustomizations($orderItem, $orderItems);
        }
        return $this;
    }

    /**
     * Check for customizations of an order item to reference only known order items.
     *
     * @param IOrderItem
     * @param IOrderItemIterable
     * @return self
     */
    protected function validateOrderItemCustomizations(IOrderItem $orderItem, IOrderItemIterable $orderItems)
    {
        $customizations = $orderItem->getCustomizations();
        foreach ($customizations as $customization) {
            $item = $customization->getCustomizedItem();
            $this->validateOptionalPayloadReference($item, 'customized item', $orderItems, $item);
        }
        return $this;
    }

    /**
     * Validate the relationship references within an order create request
     * only reference known order items in the request.
     *
     * @param IOrderCreateRequest
     * @return self
     */
    protected function validateItemRelationshipReferences(IOrderCreateRequest $payload)
    {
        $relationships = $payload->getItemRelationships();
        $orderItems = $payload->getOrderItems();
        foreach ($relationships as $relationship) {
            $parentItem = $relationship->getParentItem();
            $this->validateOptionalPayloadReference($relationship, 'parent item', $orderItems, $parentItem)
                ->validateOrderItemReferences($relationship, $orderItems);
        }
        return $this;
    }

    /**
     * Validate that the refrences in the item reference container all point
     * to valid order items.
     *
     * @param IOrderItemReferenceContainer
     * @param IOrderItemIterable
     * @return self
     */
    protected function validateOrderItemReferences(
        IOrderItemReferenceContainer $itemReferenceContainer,
        IOrderItemIterable $orderItems
    ) {
        foreach ($itemReferenceContainer->getItemReferences() as $itemReference) {
            $item = $itemReference->getReferencedItem();
            $this->validatePayloadReference($itemReference, 'referenced item', $orderItems, $item);
        }
        return $this;
    }

    /**
     * Check that a payload reference exists and points to a valid item within
     * a collection of payloads.
     *
     * @param IPayload payload that owns the reference
     * @param string a descriptive name for the reference, included in any exception messages
     * @param ArrayAccess collection of payloads the reference should resolve to
     * @param IPayload|null the referenced payload
     * @throws InvalidPayload If the referenced payload does not exists or is not included in the collection.
     */
    protected function validatePayloadReference(
        IPayload $ownerPayload,
        $referenceName,
        ArrayAccess $payloadCollection,
        IPayload $referencedPayload = null
    ) {
        if (!$referencedPayload) {
            throw new InvalidPayload(sprintf(
                '%s payload missing required %s reference.',
                get_class($ownerPayload),
                $referenceName
            ));
        }
        if (!$payloadCollection->offsetExists($referencedPayload)) {
            throw new InvalidPayload(sprintf(
                '%s payload %s reference is not included in %s.',
                get_class($ownerPayload),
                $referenceName,
                get_class($payloadCollection)
            ));
        }
        return $this;
    }

    /**
     * Check that a payload reference, if it exists, points to a valid payload
     * within a collection of payloads. Allows the reference to be excluded.
     *
     * @param IPayload payload that owns the reference
     * @param string a descriptive name for the reference, included in any exception messages
     * @param ArrayAccess collection of payloads the reference should resolve to
     * @param IPayload|null the referenced payload
     * @return self
     * @throws InvalidPayload If the referenced payload exists but is not included in the collection.
     */
    protected function validateOptionalPayloadReference(
        IPayload $ownerPayload,
        $referenceName,
        ArrayAccess $payloadCollection,
        IPayload $referencedPayload = null
    ) {
        if ($referencedPayload && !$payloadCollection->offsetExists($referencedPayload)) {
            throw new InvalidPayload(sprintf(
                '%s payload %s reference is not included in %s.',
                get_class($ownerPayload),
                $referenceName,
                get_class($payloadCollection)
            ));
        }
        return $this;
    }
}
