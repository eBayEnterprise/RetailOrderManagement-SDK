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

use eBayEnterprise\RetailOrderManagement\Payload\IIterablePayload;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;

/**
 * Iterable for allocated item payloads
 */
class AllocatedItemIterable extends \SplObjectStorage implements IAllocatedItemIterable
{
    use TIterablePayload;

    const SUBPAYLOAD_XPATH = 'x:AllocationResponse';

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
        // This payload does not have a root node, its elements
        // are located directly inside the parent payload.
        $this->buildRootNode = false;
    }

    /**
     * Get a new, empty Allocated Item
     *
     * @return IAllocatedItem
     */
    public function getEmptyAllocatedItem()
    {
        return $this->buildPayloadForInterface(static::ALLOCATED_ITEM_INTERFACE);
    }

    protected function getNewSubpayload()
    {
        return $this->getEmptyAllocatedItem();
    }

    protected function getSubpayloadXPath()
    {
        return self::SUBPAYLOAD_XPATH;
    }

    protected function getRootNodeName()
    {
        return '';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
