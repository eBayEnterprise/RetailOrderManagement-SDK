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

namespace eBayEnterprise\RetailOrderManagement\Payload;

use DateTime;
use DOMDocument;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TStrings;

/**
 * Generic implementation strategies for things payloads have to do.
 *
 * trait TPayload
 * @package eBayEnterprise\RetailOrderManagement\Payload
 */
trait TPayload
{
    use TStrings;

    /** @var IPayloadFactory */
    protected $payloadFactory;
    /** @var IPayloadMap */
    protected $payloadMap;
    /** @var ISchemaValidator */
    protected $schemaValidator;
    /** @var IValidatorIterator */
    protected $validators;
    /** @var IPayload */
    protected $parentPayload;
    /** @var array XPath expressions to extract required data from the serialized payload (XML) */
    protected $extractionPaths = [];
    /** @var array */
    protected $optionalExtractionPaths = [];
    /** @var array property/XPath pairs that take boolean values */
    protected $booleanExtractionPaths = [];
    /** @var array pair address lines properties with xpaths for extraction */
    protected $addressLinesExtractionMap = [];
    /** @var array extracting node value and assigned the property as DateTime */
    protected $datetimeExtractionPaths = [];
    /**
     * @var array property/XPath pairs. if property is a payload, first node matched
     *            will be deserialized by that payload
     */
    protected $subpayloadExtractionPaths = [];
    /** @var LoggerInterface enabled PSR-3 logging */
    protected $logger;
    /** @var bool flag to determine to either serialize empty node not or to serialize empty node. */
    protected $isSerializeEmptyNode = true;

    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $serializedPayload
     * @return $this
     */
    public function deserialize($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        foreach ($this->extractionPaths as $property => $path) {
            $this->$property = $xpath->evaluate($path);
        }
        // When optional nodes are not included in the serialized data,
        // they should not be set in the payload. Fortunately, these
        // are all string values so no additional type conversion is necessary.
        foreach ($this->optionalExtractionPaths as $property => $path) {
            $foundNode = $xpath->query($path)->item(0);
            if ($foundNode) {
                $this->$property = $foundNode->nodeValue;
            }
        }
        $this->deserializeDatetime($xpath);
        // boolean values have to be handled specially
        foreach ($this->booleanExtractionPaths as $property => $path) {
            $value = $xpath->evaluate($path);
            $this->$property = $this->convertStringToBoolean($value);
        }
        $this->addressLinesFromXPath($xpath);
        $this->deserializeLineItems($serializedPayload);
        foreach ($this->subpayloadExtractionPaths as $property => $path) {
            $foundNode = $xpath->query($path)->item(0);
            if ($foundNode && $this->$property instanceof IPayload) {
                $this->$property->deserialize($foundNode->C14N());
            }
        }
        $this->deserializeExtra($serializedPayload);
        // payload is only valid if the unserialized data is also valid
        $this->validate();
        return $this;
    }

    /**
     * Validate the serialized data via the schema validator.
     * @param  string $serializedData
     * @return $this
     */
    protected function schemaValidate($serializedData)
    {
        if ($this->schemaValidator) {
            $this->schemaValidator->validate($serializedData);
        }
        return $this;
    }

    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return \DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new DOMXPath($this->getPayloadAsDoc($xmlString));
        $xpath->registerNamespace('x', $this->getXmlNamespace());
        return $xpath;
    }

    /**
     * Load the payload XML into a DOMDocument
     * @param  string $xmlString
     * @return \DOMDocument
     */
    protected function getPayloadAsDoc($xmlString)
    {
        $d = new DOMDocument();
        try {
            $d->loadXML($xmlString);
        } catch (\Exception $e) {
            throw $e;
        }
        return $d;
    }

    /**
     * Get Line1 through Line4 for an Address
     * Find all of the nodes in the address node that
     * start with 'Line' and add their value to the
     * proper address lines array
     *
     * @param \DOMXPath $domXPath
     */
    protected function addressLinesFromXPath(\DOMXPath $domXPath)
    {
        foreach ($this->addressLinesExtractionMap as $address) {
            $lines = $domXPath->query($address['xPath']);
            $property = $address['property'];
            $this->$property = $this->addressLinesFromNodeList($lines);
        }
    }

    /**
     * Get address lines from node list
     * Return null instead of an empty array
     *
     * @param \DOMNodeList
     * @param string
     * @return array|null
     */
    protected function addressLinesFromNodeList(\DOMNodeList $lines)
    {
        $result = [];
        foreach ($lines as $line) {
            $result[] = $line->nodeValue;
        }
        return $result ?: null;
    }

    /**
     * Additional deserialization of the payload data. May contain any
     * special case deserialization that cannot be expressed by the supported
     * deserialization paths. Default implementation is a no-op. Expected to
     * be overridden by payloads that need it.
     *
     * @param string
     * @return self
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function deserializeExtra($serializedPayload)
    {
        return $this;
    }

    /**
     * convert line item substrings into line item objects
     * @param  string $serializedPayload
     * @return self
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function deserializeLineItems($serializedPayload)
    {
        return $this;
    }

    /**
     * Extract the node and if the value in the node is a non-empty
     * string value, then assigned the class property to a DateTime object
     * passing in the extracted value, otherwise assigned it to null.
     *
     * @param \DOMXPath $xpath
     * @return self
     */
    protected function deserializeDatetime(\DOMXPath $xpath)
    {
        foreach ($this->datetimeExtractionPaths as $property => $path) {
            $value = $xpath->evaluate($path);
            $this->$property = $value ? new DateTime($value) : null;
        }

        return $this;
    }

    /**
     * Build a new IPayload for the given interface.
     *
     * @param string
     * @return IPayload
     */
    protected function buildPayloadForInterface($interface)
    {
        return $this->payloadFactory->buildPayload(
            $this->payloadMap->getConcreteType($interface),
            $this->payloadMap,
            $this,
            $this->logger
        );
    }

    /**
     * Validate that the payload meets the requirements
     * for transmission. This can be over and above what
     * is required for serialization.
     *
     * @throws Exception\InvalidPayload
     */
    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }
        return $this;
    }

    /**
     * Return the string form of the payload data for transmission.
     * Validation is implied.
     *
     * @throws Exception\InvalidPayload
     * @return string
     */
    public function serialize()
    {
        // validate the payload data
        $this->validate();
        $canonicalXml = '';
        $contents = $this->serializeContents();
        if ($this->isSerializeEmptyNode || $contents) {
            $xmlString = sprintf('<%s %s>%s</%1$s>', $this->getRootNodeName(), $this->serializeRootAttributes(), $contents);
            $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
            $this->schemaValidate($canonicalXml);
        }
        return $canonicalXml;
    }

    public function getParentPayload()
    {
        return $this->parentPayload;
    }

    protected function getAncestorPayloadOfType($type)
    {
        $pl = $this;
        while ($pl = $pl->getParentPayload()) {
            if ($pl instanceof $type) {
                return $pl;
            }
        }
        return null;
    }

    /**
     * Return the name of the xml root node.
     *
     * @return string
     */
    abstract protected function getRootNodeName();

    /**
     * Serialize Root Attributes
     */
    protected function serializeRootAttributes()
    {
        $rootAttributes = $this->getRootAttributes();
        $qualifyAttributes = function ($name) use ($rootAttributes) {
            return sprintf('%s="%s"', $name, $this->xmlEncode($rootAttributes[$name]));
        };
        $qualifiedAttributes = array_map($qualifyAttributes, array_keys($rootAttributes));
        return implode(' ', $qualifiedAttributes);
    }

    /**
     * XML Namespace of the document.
     *
     * @return string
     */
    abstract protected function getXmlNamespace();

    /**
     * Name, value pairs of root attributes
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return [];
    }

    /**
     * Serialize the various parts of the payload into XML strings and concatenate them together.
     *
     * @return string
     */
    abstract protected function serializeContents();

    /**
     * Serialize the value as an xml element with the given node name. When
     * given an empty value, returns an empty string instead of an empty
     * element.
     *
     * @param string
     * @param mixed
     * @return string
     */
    protected function serializeOptionalValue($nodeName, $value)
    {
        return !is_null($value) ? $this->serializeRequiredValue($nodeName, $value) : '';
    }

    /**
     * Serialize the value as an XML attribute with the given name. When
     * given an empty value, returns an empty string.
     *
     * @param string
     * @param mixed
     * @return string
     */
    protected function serializeOptionalAttribute($attributeName, $value)
    {
        return !is_null($value) ? " $attributeName='$value' " : '';
    }

    /**
     * Serialize the currency amount as an XML node with the provided name.
     * When the amount is not set, returns an empty string.
     *
     * @param string
     * @param float
     * @return string
     */
    protected function serializeOptionalAmount($nodeName, $amount)
    {
        return !is_null($amount) ? "<$nodeName>{$this->formatAmount($amount)}</$nodeName>" : '';
    }

    /**
     * Serialize an optional date time value. When a DateTime value is given,
     * serialize the DateTime object as an XML node containing the DateTime
     * formatted with the given format. When no DateTime is given, return
     * an empty string.
     *
     * @param string
     * @param string
     * @param DateTime
     * @return string
     */
    protected function serializeOptionalDateValue($nodeName, $format, DateTime $date = null)
    {
        return $date ? "<$nodeName>{$date->format($format)}</$nodeName>" : '';
    }

    /**
     * Get a new PayloadFactory instance.
     *
     * @return PayloadFactory
     */
    protected function getNewPayloadFactory()
    {
        return new PayloadFactory();
    }

    /**
     * Serialize a value with a given name. Value will be XML encoded before
     * being serialized.
     *
     * @param string
     * @param string
     * @return string
     */
    protected function serializeXmlEncodedValue($name, $value)
    {
        return $this->serializeRequiredValue($name, $this->xmlEncode($value));
    }

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    protected function serializeOptionalXmlEncodedValue($name, $value)
    {
        return $this->serializeOptionalValue($name, $this->xmlEncode($value));
    }

    /**
     * serialize a required field to an xml string
     *
     * @param string
     * @param string
     * @return string
     */
    protected function serializeRequiredValue($name, $value)
    {
        return "<$name>$value</$name>";
    }
}
