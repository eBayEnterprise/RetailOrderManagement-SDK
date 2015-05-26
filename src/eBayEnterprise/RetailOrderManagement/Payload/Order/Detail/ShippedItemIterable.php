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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

class ShippedItemIterable extends SPLObjectStorage implements IShippedItemIterable
{
    use TIterablePayload;

    const ROOT_NODE = 'ShippedItems';
    const SUBPAYLOAD_XPATH = 'x:ShippedItem';

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
        $this->payloadFactory = $this->getNewPayloadFactory();
    }

    /**
     * @see IShippedItemIterable::getEmptyShippedItem()
     */
    public function getEmptyShippedItem()
    {
        return $this->buildPayloadForInterface(static::SHIPPED_ITEM_INTERFACE);
    }

    /**
     * Get a new shipped item instance.
     * @return IShippedItem
     */
    protected function getNewSubpayload()
    {
        return $this->getEmptyShippedItem();
    }

    /**
     * @see TIterablePayload::getSubpayloadXPath()
     */
    protected function getSubpayloadXPath()
    {
        return static::SUBPAYLOAD_XPATH;
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
