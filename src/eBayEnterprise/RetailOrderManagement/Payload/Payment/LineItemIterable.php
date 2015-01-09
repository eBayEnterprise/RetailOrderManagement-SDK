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

use eBayEnterprise\RetailOrderManagement\Payload;
use SPLObjectStorage;

class LineItemIterable extends SPLObjectStorage implements ILineItemIterable
{
    use Payload\TIterablePayload;

    const LINE_ITEM_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem';

    public function __construct(
        Payload\IValidatorIterator $validators,
        Payload\ISchemaValidator $schemaValidator,
        Payload\IPayloadMap $payloadMap
    ) {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new Payload\PayloadFactory();
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
        return $this->payloadFactory->buildPayload(
            $this->payloadMap->getConcreteType(static::LINE_ITEM_INTERFACE),
            $this->payloadMap
        );
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
