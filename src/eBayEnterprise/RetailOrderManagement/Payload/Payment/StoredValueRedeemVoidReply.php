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
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\Exception;

/**
 * StoredValueRedeemVoidReply Payload
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 */
class StoredValueRedeemVoidReply implements IStoredValueRedeemVoidReply
{
    /** @var string $orderId id of the order */
    protected $orderId;
    /** @var string $cardNumber Gift tender payment account unique id (PAN) */
    protected $cardNumber;
    /** @var bool $panIsToken Indicates if the PAN is the actual number, or a representation of the number. */
    protected $panIsToken;
    protected $responseCode;
    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;
    /** @var array XPath expressions to extract required data from the serialized payload (XML) */
    protected $extractionPaths = [
        'orderId' => 'string(x:PaymentContext/x:OrderId)',
        'cardNumber' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
        'responseCode' => ' string(x:ResponseCode)',
    ];
    /** @var array property/XPath pairs that take boolean values*/
    protected $booleanXPaths = [
        'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)'
    ];
    /**
     * @param IValidatorIterator $validators Payload object validators
     * @param ISchemaValidator $schemaValidator Serialized object schema validator
     */
    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }
    /**
     * Id of the order.
     *
     * xsd restrictions: 1-20 characters
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }
    /**
     * @return int
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
        return $this;
    }
    /**
     * Indicates if the account Id is the actual number, or a representation of the number.
     *
     * @return bool true if the Id is a token, false if it's the actual number
     */
    public function getPanIsToken()
    {
        return $this->panIsToken;
    }
    /**
     * @param bool $isToken
     * @return self
     */
    public function setPanIsToken($isToken)
    {
        $this->panIsToken = $isToken;
        return $this;
    }
    /**
     * Either a tokenized or plain text stored value account id.
     *
     * xsd restrictions: 1-22 characters
     * @see get/setPanIsToken
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }
    /**
     * @param string $cardNumber
     * @return self
     */
    public function setCardNumber($cardNumber)
    {
        $this->cardNumber = $cardNumber;
        return $this;
    }
    /**
     * The amount to void.
     *
     * xsd note: enumeration, pattern (Fail|Success|Timeout)
     * return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
    /**
     * @param string
     * @return self
     */
    public function setResponseCode($code)
    {
        $this->responseCode = $code;
        return $this;
    }
    /**
     * Validate that the payload meets the requirements
     * for transmission. This can be over and above what
     * is required for serialization.
     *
     * @throws Exception\InvalidPayload
     * @return self
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
            '<%s xmlns="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->serializeContents()
        );
        $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
        $this->schemaValidate($canonicalXml);
        return $canonicalXml;
    }
    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $string
     * @return self
     */
    public function deserialize($serializedPayload)
    {
        // make sure we received a valid serialization of the payload.
        $this->schemaValidate($serializedPayload);

        $xpath = $this->getPayloadAsXPath($serializedPayload);
        foreach ($this->extractionPaths as $property => $path) {
            $this->$property = $xpath->evaluate($path);
        }
        // boolean values have to be handled specially
        foreach ($this->booleanXPaths as $property => $path) {
            $value = $xpath->evaluate($path);
            $this->$property = $this->booleanFromString($value);
        }

        // payload is only valid if the unserialized data is also valid
        $this->validate();
        return $this;
    }
    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentContext()
            . $this->serializeResponseCode();
    }

    /**
     * Create an XML string representing the PaymentContext nodes
     * @return string
     */
    protected function serializePaymentContext()
    {
        return sprintf(
            '<PaymentContext><OrderId>%s</OrderId><PaymentAccountUniqueId isToken="%s">%s</PaymentAccountUniqueId></PaymentContext>',
            $this->getOrderId(),
            $this->getPanIsToken() ? 'true' : 'false',
            $this->getCardNumber()
        );
    }

    /**
     * Create an XML string representing the response code.
     * @return string
     */
    protected function serializeResponseCode()
    {
        return sprintf(
            '<ResponseCode>%s</ResponseCode>',
            $this->getResponseCode()
        );
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
     * Validate the serialized data via the schema validator.
     * @param  string $serializedData
     * @return self
     */
    protected function schemaValidate($serializedData)
    {
        $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        return $this;
    }
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
    }
    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new \DOMXPath($this->getPayloadAsDoc($xmlString));
        $xpath->registerNamespace('x', self::XML_NS);
        return $xpath;
    }
    /**
     * Convert "true", "false", "1" or "0" to boolean
     * Everything else returns null
     *
     * @param $string
     * @return bool|null
     */
    protected function booleanFromString($string)
    {
        if (!is_string($string)) {
            return null;
        }
        $string = strtolower($string);
        return (($string === 'true') || ($string === '1'));
    }
}
