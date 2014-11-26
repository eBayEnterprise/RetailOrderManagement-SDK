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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use SimpleXMLElement;

class TestMessage implements ITestMessage
{
    use TPayload;

    /** @var DateTime */
    protected $timestamp;

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }
    public function getEventType()
    {
        return self::ROOT_NODE;
    }
    public function getTimestamp()
    {
        return $this->timestamp;
    }
    public function setTimestamp(DateTime $timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }
    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }
        return $this;
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return '';
    }
    public function serialize()
    {
        $this->validate();
        $xml = sprintf(
            '<%s xmlns="%s" timestamp="%s"/>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->getTimestamp()->format('c')
        );
        $this->schemaValidate($xml);
        return $xml;
    }
    public function deserialize($string)
    {
        $this->schemaValidate($string);
        $ele = new SimpleXMLElement($string);
        $this->setTimestamp(new DateTime($ele['timestamp']));
        return $this;
    }
    /**
     * Validate the serialized data via the schema validator.
     * @param  string $serializedData
     * @return self
     */
    protected function schemaValidate($serializedData)
    {
        $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        return $this;
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . static::XSD;
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
}
