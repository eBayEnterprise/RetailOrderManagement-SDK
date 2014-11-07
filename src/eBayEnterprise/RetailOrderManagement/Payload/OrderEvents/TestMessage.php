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
use SimpleXMLElement;

class TestMessage implements ITestMessage
{
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
        return __DIR__ . '/schema/' . self::XSD;
    }
}
