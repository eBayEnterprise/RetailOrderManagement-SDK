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
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LineItem implements ILineItem
{
    use TPayload, TAmount;

    /** @var string */
    protected $name;
    /** @var string */
    protected $sequenceNumber;
    /** @var int */
    protected $quantity;
    /** @var float */
    protected $unitAmount;
    /** @var float */
    protected $currencyCode;

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
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'name' => 'string(x:Name)',
            'quantity' => 'number(x:Quantity)',
        ];
        $this->optionalExtractionPaths = [
            'unitAmount' => 'x:UnitAmount',
            'currencyCode' => 'x:UnitAmount/@currencyCode',
            'sequenceNumber' => 'x:SequenceNumber',
        ];
    }

    /**
     * convert the data into an xml string
     * @return string
     * @throws \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    protected function serializeContents()
    {
        return "<Name>{$this->xmlEncode($this->getName())}</Name>"
        . $this->serializeSequenceNumber()
        . "<Quantity>{$this->getQuantity()}</Quantity>"
        . $this->serializeUnitAmount();
    }

    /**
     * Line item name like product title.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * serialize the sequence number as an xml string
     * @return string
     */
    protected function serializeSequenceNumber()
    {
        return $this->serializeOptionalXmlEncodedValue('SequenceNumber', $this->getSequenceNumber());
    }

    /**
     * Sequence number of current line item in cart if available.
     *
     * @return string
     */
    public function getSequenceNumber()
    {
        return $this->sequenceNumber;
    }

    /**
     * @param string
     * @return self
     */
    public function setSequenceNumber($num)
    {
        $this->sequenceNumber = $num;
        return $this;
    }

    /**
     * Quantity for this line item.
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int
     * @return self
     */
    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;
        return $this;
    }

    /**
     * serialize the unit amount as an xml string
     * @return string
     */
    protected function serializeUnitAmount()
    {
        return $this->serializeCurrencyAmount(
            'UnitAmount',
            $this->getUnitAmount(),
            $this->xmlEncode($this->getCurrencyCode())
        );
    }

    /**
     * Unit price amount for a line item.
     *
     * @return float
     */
    public function getUnitAmount()
    {
        return $this->unitAmount;
    }

    /**
     * @param float
     * @return self
     */
    public function setUnitAmount($amount)
    {
        $this->unitAmount = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * ISO 4217:2008 code that represents the currency for the unit amount.
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($code)
    {
        $this->currencyCode = $code;
        return $this;
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
     * Return the name of the xml root node.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
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
