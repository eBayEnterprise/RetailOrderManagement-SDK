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

use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;

/**
 * Class StoredValueRedeemRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueRedeemRequest implements IStoredValueRedeemRequest
{
    use TPaymentContext;
    /** @var string */
    protected $requestId;
    /** @var string */
    protected $pin;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $currencyCode;
    /** @var array */
    protected $extractionPaths = [
        'orderId' => 'string(x:PaymentContext/x:OrderId)',
        'cardNumber' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
        'amount' => 'number(x:Amount)',
        'currencyCode' => 'string(x:Amount/@currencyCode)',
        'requestId' => 'string(@requestId)',
    ];
    /** @var array property/XPath pairs that take boolean values*/
    protected $booleanXPaths = [
        'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)',
    ];
    protected $optionalExtractionPaths = [
        'pin' => 'x:Pin',
    ];

    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;
    protected $responseCode;

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    /**
     * Trim any white space and return the resulting string truncating to $maxLength.
     *
     * Return null if the result is an empty string or not a string
     *
     * @param string $string
     * @param int $maxLength
     * @return string or null
     */
    protected function cleanString($string, $maxLength)
    {
        $value = null;

        if (is_string($string)) {
            $trimmed = substr(trim($string), 0, $maxLength);
            $value = empty($trimmed) ? null : $trimmed;
        }

        return $value;
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function setPin($pin)
    {
        $this->pin = $this->cleanString($pin, 8);
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        if (is_float($amount)) {
            $this->amount = round($amount, 2, PHP_ROUND_HALF_UP);
        } else {
            $this->amount = null;
        }
        return $this;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode($code)
    {
        $value = null;

        $cleaned = $this->cleanString($code, 3);
        if ($cleaned !== null) {
            if (!strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->currencyCode = $value;

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
            . $this->serializePin()
            . $this->serializeAmounts('');
    }

    /**
     * Build the Pin node
     *
     * @return string
     */
    protected function serializePin()
    {
        $pin = $this->getPin();
        if ($pin === '') {
            return '';
        }
        return "<Pin>{$this->getPin()}</Pin>";
    }

    /**
     * Build the Amount node
     * @param string $amountType either 'AmountRedeemed' or 'BalanceAmount'
     * @return string
     */
    protected function serializeAmounts($amountType)
    {
        return sprintf('<Amount currencyCode="%s">%1.02F</Amount>', $this->getCurrencyCode(), $this->getAmount());
    }

    /**
     * The result of the request transaction.
     *
     * xsd note: possible values: Fail, Success, Timeout
     * @return string
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
            '<%s %s>%s</%1$s>',
            self::ROOT_NODE,
            $this->serializeRootAttrs(),
            $this->serializeContents()
        );
        $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
        $this->schemaValidate($canonicalXml);
        return $canonicalXml;
    }

    /**
     * Serialize Root Attributes
     */
    protected function serializeRootAttrs()
    {
        return sprintf(
            '%s="%s" %s="%s"',
            'xmlns',
            static::XML_NS,
            'requestId',
            $this->getRequestId()
        );
    }

    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string
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

        // payload is only valid of the unserialized data is also valid
        $this->validate();
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

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCodeRedeemed($code)
    {
        $this->currencyCode = $code;
        return $this;
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

    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::PAYLOAD_SCHEMA;
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
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * xsd restrictions: 1-40 characters
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $this->cleanString($requestId, 40);
        return $this;
    }
}
