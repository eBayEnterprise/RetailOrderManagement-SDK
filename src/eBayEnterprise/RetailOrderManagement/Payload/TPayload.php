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

    /** @var ISchemaValidator */
    protected $schemaValidator;
    /** @var IValidatorIterator */
    protected $validators;
    /** @var array XPath expressions to extract required data from the serialized payload (XML) */
    protected $extractionPaths = [];
    /** @var array */
    protected $optionalExtractionPaths = [];
    /** @var array property/XPath pairs that take boolean values */
    protected $booleanExtractionPaths = [];

    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $serializedPayload
     * @return $this
     */
    public function deserialize($serializedPayload)
    {
        // make sure we received a valid serialization of the payload.
        $this->schemaValidate($serializedPayload);

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
        // boolean values have to be handled specially
        foreach ($this->booleanExtractionPaths as $property => $path) {
            $value = $xpath->evaluate($path);
            $this->$property = $this->convertStringToBoolean($value);
        }

        $this->deserializeLineItems($serializedPayload);

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
            $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        }
        return $this;
    }

    /**
     * Return the schema file path.
     *
     * @return string
     */
    abstract protected function getSchemaFile();

    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return \DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new \DOMXPath($this->getPayloadAsDoc($xmlString));
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
        $d = new \DOMDocument();
        $d->loadXML($xmlString);
        return $d;
    }

    /**
     * The XML namespace for the payload.
     *
     * @return string
     */
    abstract protected function getXmlNamespace();

    /**
     * convert line item substrings into line item objects
     * @param  string $serializedPayload
     * @return self
     */
    protected function deserializeLineItems($serializedPayload)
    {
        return $this;
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
        $xmlString = sprintf(
            '<%s %s>%s</%1$s>',
            $this->getRootNodeName(),
            $this->serializeRootAttributes(),
            $this->serializeContents()
        );
        $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
        $this->schemaValidate($canonicalXml);
        return $canonicalXml;
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
            return sprintf('%s="%s"', $name, $rootAttributes[$name]);
        };
        $qualifiedAttributes = array_map($qualifyAttributes, array_keys($rootAttributes));
        return implode(' ', $qualifiedAttributes);
    }

    /**
     * Name, value pairs of root attributes
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
        ];
    }

    /**
     * Serialize the various parts of the payload into XML strings and concatenate them together.
     *
     * @return string
     */
    abstract protected function serializeContents();
}
