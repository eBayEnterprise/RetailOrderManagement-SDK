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
    use TAmount;

    const LINE_ITEM_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem';

    /** @var float */
    protected $shippingTotal;
    /** @var float */
    protected $lineItemsTotal;
    /** @var float */
    protected $taxTotal;
    /** @var string */
    protected $currencyCode;
    /** @var \eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap */
    protected $payloadMap;
    /** @var \eBayEnterprise\RetailOrderManagement\Payload\IPayloadFactory */
    protected $payloadFactory;
    /** @var \eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var \eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator */
    protected $schemaValidator;

    protected $extractionPaths = [
        'shippingTotal' => 'number(x:ShippingTotal)',
        'taxTotal' => 'number(x:TaxTotal)',
        // get the currency code from any of the fields that exist
        'currencyCode' => 'string(x:TaxTotal/@currencyCode)',
    ];

    // optional elements including the type to cast the value to
    protected $optionalExtractionPaths = [
        'lineItemsTotal' => ['float', 'x:LineItemsTotal'],
    ];

    public function __construct(
        Payload\IValidatorIterator $iterator,
        Payload\ISchemaValidator $schemaValidator,
        Payload\IPayloadMap $payloadMap
    ) {
        $this->validatorIterator = $iterator;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new Payload\PayloadFactory();
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
     * Total amount for all line items excluding shipping and tax; calculation works as follows
     * LineItemsTotal = First-LineItem-Quantity * First-LineItem--Amount + next one;
     * PayPal validates above calculation and throws error message for incorrect line items total;
     * LineItemsTotal must always be greater than 0.
     *
     * @return float
     */
    public function getLineItemsTotal()
    {
        return $this->lineItemsTotal;
    }

    /**
     * @param float
     * @return self
     */
    public function setLineItemsTotal($amount)
    {
        $this->lineItemsTotal = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * Total shipping amount for all line items.
     *
     * @return float
     */
    public function getShippingTotal()
    {
        return $this->shippingTotal;
    }

    /**
     * @param float
     * @return self
     */
    public function setShippingTotal($amount)
    {
        $this->shippingTotal = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * Total tax amount for all line items.
     *
     * @return float
     */
    public function getTaxTotal()
    {
        return $this->taxTotal;
    }

    /**
     * @param float
     * @return self
     */
    public function setTaxTotal($amount)
    {
        $this->taxTotal = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
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
     * calculate and set the line items total.
     * @return self
     */
    public function calculateLineItemsTotal()
    {
        $total = 0.0;
        foreach ($this as $item) {
            $total += $item->getUnitAmount() * $item->getQuantity();
        }
        $this->setLineItemsTotal($total);
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
        return '<LineItems>'
            . $this->serializeLineItemsTotal()
            . $this->serializeShippingTotal()
            . $this->serializeTaxTotal()
            . $this->serializeLineItems()
            . '</LineItems>';
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
                // enforce the proper type
                settype($this->$property, $type);
            }
        }

        $this->deserializeLineItems($serializedPayload);

        // payload is only valid if the unserialized data is valid
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
     * convert line item substrings into line item objects
     * @param  string $serializedPayload
     * @return self
     */
    protected function deserializeLineItems($serializedPayload)
    {
        $startTag = '<'.ILineItem::ROOT_NODE .'>';
        $endTag = '</'.ILineItem::ROOT_NODE .'>';
        $startTagPos = strpos($serializedPayload, $startTag);
        if ($startTagPos === false) {
            return $this;
        }
        $endTagPos = strpos($serializedPayload, $endTag, $startTagPos);
        $chunk = substr($serializedPayload, $startTagPos, $endTagPos - $startTagPos + strlen($endTag));
        $lineItem = $this->getEmptyLineItem()->deserialize($chunk);
        $this->attach($lineItem);
        $this->deserializeLineItems(substr($serializedPayload, $startTagPos + strlen($chunk)));
        return $this;
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
        $xpath->registerNamespace('x', static::XML_NS);
        return $xpath;
    }

    /**
     * serialize the data for the LineItemsTotal element
     * @return string
     */
    protected function serializeLineItemsTotal()
    {
        return $this->serializeCurrencyAmount('LineItemsTotal', $this->getLineItemsTotal(), $this->getCurrencyCode());
    }

    /**
     * serialize the data for the ShippingTotal element
     * @return string
     */
    protected function serializeShippingTotal()
    {
        return $this->serializeCurrencyAmount('ShippingTotal', $this->getShippingTotal(), $this->getCurrencyCode());
    }

    /**
     * serialize the data for the TaxTotal element
     * @return string
     */
    protected function serializeTaxTotal()
    {
        return $this->serializeCurrencyAmount('TaxTotal', $this->getTaxTotal(), $this->getCurrencyCode());
    }

    /**
     * serialize the contained line item objects.
     * @return string
     */
    protected function serializeLineItems()
    {
        $output = '';
        foreach ($this as $lineItem) {
            $output .= $lineItem->serialize();
        }
        return $output;
    }

    /**
     * Override the SPLObjectStorage's unserialize method
     * @param  string $string
     */
    public function unserialize($string)
    {
        $this->deserialize($string);
    }
}
