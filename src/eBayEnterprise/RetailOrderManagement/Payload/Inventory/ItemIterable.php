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
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use \SplObjectStorage;

class ItemIterable extends SplObjectStorage implements IItemIterable, IUnavailableItemIterable, IDetailItemIterable
{
    use TIterablePayload;

    const ROOT_NODE = '';
    const SUBPAYLOAD_PATH = '';

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
        $this->buildRootNode = false;
    }

    /**
     * Get a new, empty item for shipping.
     *
     * @return IShippingItemIterable
     */
    public function getEmptyShippingItem()
    {
        return $this->buildPayloadForInterface(static::SHIPPING_ITEM_INTERFACE);
    }

    /**
     * Get a new, empty item for in store pick up.
     *
     * @return IInStorePickUpItemIterable
     */
    public function getEmptyInStorePickUpItem()
    {
        return $this->buildPayloadForInterface(static::INSTOREPICKUP_ITEM_INTERFACE);
    }

    /**
     * Get a new, empty payload for an unavailable item.
     *
     * @return IShippingItemIterable
     */
    public function getEmptyDetailItem()
    {
        return $this->buildPayloadForInterface(static::DETAIL_ITEM_INTERFACE);
    }

    /**
     * Get a new, empty payload for an unavailable item.
     *
     * @return IShippingItemIterable
     */
    public function getEmptyUnavailableItem()
    {
        return $this->buildPayloadForInterface(static::UNAVAILABLE_ITEM_INTERFACE);
    }

    protected function getSubPayloadXPath()
    {
        return static::SUBPAYLOAD_PATH;
    }

    protected function getNewSubpayload()
    {
        return null;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }
}
