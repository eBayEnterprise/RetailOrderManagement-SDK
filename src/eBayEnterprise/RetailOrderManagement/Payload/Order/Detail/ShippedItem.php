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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ShippedItem implements IShippedItem
{
    use TPayload, TOrderDetailTrackingNumberContainer;

    /** @var string */
    protected $item;
    /** @var string */
    protected $ref;
    /** @var float */
    protected $quantity;
    /** @var string */
    protected $invoiceNo;

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
        $this->parentPayload = $parentPayload;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = $this->getNewPayloadFactory();

        $this->initExtractPaths()
            ->initOptionalExtractPaths()
            ->initSubPayloadExtractPaths()
            ->initSubPayloadProperties();
    }

    /**
     * Initialize the protected class property array self::extractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initExtractPaths()
    {
        $this->extractionPaths = [
            'item' => 'string(x:Item)',
            'ref' => 'string(x:Item/@ref)',
            'quantity' => "string(x:Quantity)",
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::optionalExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initOptionalExtractPaths()
    {
        $this->optionalExtractionPaths = [
            'invoiceNo' => 'x:InvoiceNo',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::subpayloadExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initSubPayloadExtractPaths()
    {
        $this->subpayloadExtractionPaths = [
            'trackingNumbers' => 'x:TrackingNumbers',
        ];
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setOrderDetailTrackingNumbers($this->buildPayloadForInterface(
            static::TRACKING_NUMBER_ITERABLE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IShippedItem::getId()
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @see IShippedItem::setItem()
     * @codeCoverageIgnore
     */
    public function setItem($item)
    {
        $this->item = $item;
        return $this;
    }

    /**
     * @see IShippedItem::getRef()
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @see IShippedItem::setRef()
     * @codeCoverageIgnore
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
        return $this;
    }

    /**
     * @see IShippedItem::getQuantity()
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @see IShippedItem::setQuantity()
     * @codeCoverageIgnore
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @see IShippedItem::getInvoiceNo()
     */
    public function getInvoiceNo()
    {
        return $this->invoiceNo;
    }

    /**
     * @see IShippedItem::setInvoiceNo()
     * @codeCoverageIgnore
     */
    public function setInvoiceNo($invoiceNo)
    {
        $this->invoiceNo = $invoiceNo;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeItemValue('Item', $this->getItem())
            . $this->serializeRequiredValue('Quantity', $this->xmlEncode($this->getQuantity()))
            . $this->serializeOptionalXmlEncodedValue('InvoiceNo', $this->getInvoiceNo())
            . $this->getOrderDetailTrackingNumbers()->serialize();
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    /**
     * Serializing the item XML node.
     *
     * @param  string
     * @param  string
     * @return string
     */
    protected function serializeItemValue($nodeName, $value)
    {
        $refAttribute = $this->serializeOptionalAttribute('ref', $this->xmlEncode($this->getRef()));
        return sprintf('<%s %s>%s</%1$s>', $nodeName, $refAttribute, $this->xmlEncode($value));
    }
}
