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

/**
 * <code>
 * $storedValueBalanceRequest = new StoredValueBalanceRequest();
 * $storedValueBalanceRequest
 *    ->setPanIsToken($isToken)
 *    ->setPan($pan)
 *    ->setPin($pin)
 *    ->setCurrencyCode($code);
 * ...
 * </code>
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\Exception;

/**
 * Class StoredValueBalanceRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueBalanceRequest implements IStoredValueBalanceRequest
{
    const ROOT_NODE = 'StoredValueBalanceRequest';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = 'schema/Payment-Service-StoredValueBalance-1.0.xsd';

    /** @var string $cardNumber */
    protected $cardNumber;
    /** @var bool $panIsToken Indicates if the card number is the actual number, or a representation of the number. */
    protected $pin;
    protected $panIsToken;
    protected $currencyCode;
    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;
    /** @var array XPath expressions to extract required data from the serialized payload (XML) */
    protected $extractionPaths = array(
        'cardNumber' => 'string(x:PaymentAccountUniqueId)',
        'currencyCode' => 'string(x:CurrencyCode)',
    );
    protected $optionalExtractionPaths = array(
        'pin' => 'x:Pin',
    );
    /** @var array property/XPath pairs that take boolean values*/
    protected $booleanXPaths = array(
        'panIsToken' => 'string(x:PaymentAccountUniqueId/@isToken)'
    );
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
     * Indicates if the Card Number is the actual number, or a representation of the number.
     *
     * @return bool true if the card number is a token, false if it's the actual number
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
    }

    /**
     * Either a tokenized or plain text payment account unique id.
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
    }

    /**
     * The personal identification number or code associated with a giftcard
     * account unique id
     *
     * TODO: FIND OUT IF SERVICE WILL COMPLAIN IF PIN IS
     *       BLANK
     *
     * xsd note: minLength 1; maxLength 8
     *           pattern (\d{1,8})?
     * return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @param string $pin
     * @return self
     */
    public function setPin($pin)
    {
        $this->pin = $pin;
    }

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $code
     * @return self
     */
    public function setCurrencyCode($code)
    {
        $this->currencyCode = $code;
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
     * @param string $serializedPayload
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
        return $this->serializePan()
            . $this->serializePin()
            . sprintf(
                '<CurrencyCode>%s</CurrencyCode>',
                $this->getCurrencyCode()
            );
    }

    /**
     * Return the XML representation of the PIN if it exists;
     * otherwise, return the empty string.
     * @return string
     */
    protected function serializePin()
    {
        return $this->pin ? sprintf('<Pin>%s</Pin>', $this->getPin()) : '';
    }

    /**
     * Create an XML string representing the PaymentContext nodes
     * @return string
     */
    protected function serializePan()
    {
        return sprintf(
            '<PaymentAccountUniqueId isToken="%s">%s</PaymentAccountUniqueId>',
            $this->getPanIsToken() ? 'true' : 'false',
            $this->getCardNumber()
        );
    }

    // all methods below should be refactored as they are literal copies
    // from somewhere else

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
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
     * @return DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new \DOMXPath($this->getPayloadAsDoc($xmlString));
        $xpath->registerNamespace('x', self::XML_NS);
        return $xpath;
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
