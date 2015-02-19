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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

class LineItemIterable extends SPLObjectStorage implements ILineItemIterable
{
    use TIterablePayload;

    const LINE_ITEM_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem';
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
        $this->payloadFactory = new PayloadFactory();
        $this->includeIfEmpty = false;
        $this->buildRootNode = false;
    }

    /**
     * Get a new payload that can be put into the iterable.
     *
     * @return IPayload
     */
    protected function getNewSubpayload()
    {
        return $this->getEmptyLineItem();
    }

    /**
     * Get an XPath expression that will separate the serialized data into
     * XML for each subpayload in the iterable.
     *
     * @return string
     */
    protected function getSubpayloadXPath()
    {
        return 'x:' . static::SUBPAYLOAD_XPATH;
    }

    /**
     * Template for the line item.
     *
     * @return ILineItem
     */
    public function getEmptyLineItem()
    {
        return $this->buildPayloadForInterface(static::LINE_ITEM_INTERFACE);
    }

    /**
     * Return the name of the xml root node.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * The XML namespace for the payload.
     *
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    /**
     * Name, value pairs of root attributes
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return [];
    }
}
