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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;

/**
 * Results of a request to allocation inventory for an order
 */
class AllocationReply implements IAllocationReply
{
    use TTopLevelPayload;

    /** @var string */
    protected $reservationId;
    /** @var IIterablePayload */
    protected $allocatedItems;

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
        list(
            $this->validators,
            $this->schemaValidator,
            $this->payloadMap,
            $this->logger,
            $this->parentPayload
        ) = func_get_args();
        $this->payloadFactory = $this->getNewPayloadFactory();

        $this->allocatedItems = $this->buildPayloadForInterface(
            static::ALLOCATED_ITEM_ITERABLE_INTERFACE
        );
        $this->optionalExtractionPaths = [
            'reservationId' => '@reservationId',
        ];
    }

    /**
     * Identifies the inventory reservation which is created by this operation.
     *
     * restrictions: optional
     * @return string
     */
    public function getReservationId()
    {
        return $this->reservationId;
    }

    /**
     * @param string
     * @return self
     */
    public function setReservationId($reservationId)
    {
        $this->reservationId = $reservationId;
        return $this;
    }

    /**
     * Collection of items and their allocation status.
     *
     * @return IIterablePayload
     */
    public function getAllocatedItems()
    {
        return $this->allocatedItems;
    }

    /**
     * @param IIterablePayload
     * @return self
     */
    public function setAllocatedItems(IIterablePayload $items)
    {
        $this->allocatedItems = $items;
        return $this;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload::getSchemaFile
     * @return string
     */
    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . static::XSD;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload::getRootAttributes
     * @return string
     */
    protected function getRootAttributes()
    {
        $attrs = ['xmlns' => $this->getXmlNamespace()];
        if ($this->getReservationId()) {
            $attrs['reservationId'] = $this->cleanString($this->getReservationId(), 40);
        }
        return $attrs;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::getRootNodeName
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::serializeContents
     * @return string
     */
    protected function serializeContents()
    {
        return $this->allocatedItems->serialize();
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::deserializeLineItems
     * @param self
     */
    protected function deserializeLineItems($serializedPayload)
    {
        $this->allocatedItems->deserialize($serializedPayload);
        return $this;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::getXmlNamespace
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
