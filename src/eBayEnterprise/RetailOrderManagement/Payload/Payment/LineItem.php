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

class LineItem implements ILineItem
{
    use TAmount;

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
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;

    protected $extractionPaths = [
        'name' => 'string(x:Name)',
        'quantity' => 'number(x:Quantity)',
    ];

    // optional elements including the type to cast the value to
    protected $optionalExtractionPaths = [
        'unitAmount' => ['integer', 'x:UnitAmount'],
        'currencyCode' => ['string', 'x:UnitAmount/@currencyCode'],
        'sequenceNumber' => ['string', 'x:SequenceNumber'],
    ];

    public function __construct(Payload\IValidatorIterator $iterator)
    {
        $this->validatorIterator = $iterator;
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
        $this->quantity = $quantity;
        return $this;
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
     * convert the data into an xml string
     * @return string
     * @throws \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function serialize()
    {
        $this->validate();
        return '<'.self::ROOT_NODE.'>'
            . "<Name>{$this->getName()}</Name>"
            . $this->serializeSequenceNumber()
            . "<Quantity>{$this->getQuantity()}</Quantity>"
            . $this->serializeUnitAmount()
            . '</'.self::ROOT_NODE.'>';
    }

    /**
     * convert an xml string to data elements.
     * @param  string $serializedPayload
     * @return self
     */
    public function deserialize($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        foreach ($this->extractionPaths as $property => $path) {
            $this->$property = $xpath->evaluate($path);
        }
        // When optional nodes are not included in the serialized data,
        // they should not be set in the payload.
        foreach ($this->optionalExtractionPaths as $property => $rule) {
            list($type, $path) = $rule;
            $foundNode = $xpath->query($path)->item(0);
            if ($foundNode) {
                $this->$property = $foundNode->nodeValue;
                // type enforcement is required for the quantity to be an int
                // and the sequence number to be a string
                settype($this->$property, $type);
            }
        }
        // payload is only valid of the unserialized data is also valid
        $this->validate();
        return $this;
    }

    /**
     * validate the payload.
     * @return self
     */
    public function validate()
    {
        foreach ($this->validatorIterator as $validator) {
            $validator->validate($this);
        }
        return $this;
    }

    /**
     * serialize the unit amount as an xml string
     * @return string
     */
    protected function serializeUnitAmount()
    {
        if ($this->getUnitAmount()) {
            return $this->serializeCurrencyAmount(
                'UnitAmount',
                $this->getUnitAmount(),
                $this->getCurrencyCode()
            );
        }
        return '';
    }

    /**
     * serialize the sequence number as an xml string
     * @return string
     */
    protected function serializeSequenceNumber()
    {
        return $this->getSequenceNumber() ? "<SequenceNumber>{$this->getSequenceNumber()}</SequenceNumber>" : '';
    }

    /**
     * Load the payload XML into a DOMDocument
     * @param  string $xmlString
     * @return \DOMDocument
     */
    protected function getPayloadAsDoc($xmlString)
    {
        $d = new \DOMDocument();
        $d->loadXML($xmlString);
        return $d;
    }

    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return \DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new \DOMXPath($this->getPayloadAsDoc($xmlString));
        $xpath->registerNamespace('x', self::XML_NS);
        return $xpath;
    }
}
