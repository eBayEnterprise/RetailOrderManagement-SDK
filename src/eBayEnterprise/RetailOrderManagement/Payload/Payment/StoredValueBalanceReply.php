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

use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

/**
 * Class StoredValueBalanceReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueBalanceReply implements IStoredValueBalanceReply
{
    use TPayload, TPaymentAccountUniqueId;
    /** @var float */
    protected $balanceAmount;
    /** @var string */
    protected $currencyCode;
    /** @var string */
    protected $responseCode;
    /** @var array response codes that are considered a success */
    protected $successResponseCodes = ['Success'];

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'cardNumber' => 'string(x:EncryptedPaymentAccountUniqueId|x:PaymentAccountUniqueId)',
            'balanceAmount' => 'number(x:BalanceAmount)',
            'currencyCode' => 'string(x:BalanceAmount/@currencyCode)',
            'responseCode' => 'string(x:ResponseCode)',
        ];
        /** @var array property/XPath pairs that take boolean values*/
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentAccountUniqueId/@isToken)'
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    public function getBalanceAmount()
    {
        return $this->balanceAmount;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }
    public function setResponseCode($code)
    {
        $this->responseCode = $code;
        return $this;
    }

    /**
     * @param float
     * @return self
     */
    public function setBalanceAmount($amount)
    {
        $this->balanceAmount = $amount;
        return $this;
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
     * Serialize the data into a string of XML.
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

    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }
        return $this;
    }

    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . static::XSD;
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

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentAccountUniqueId()
        . $this->serializeResponseCodes()
        . $this->serializeAmount();
    }

    /**
     * Create an XML string representing the response code from ROM.
     * @return string
     */
    protected function serializeResponseCodes()
    {
        return "<ResponseCode>{$this->getResponseCode()}</ResponseCode>";
    }

    /**
     * Create an XML string representing the amount authorized.
     * @return string
     */
    protected function serializeAmount()
    {
        return sprintf(
            '<BalanceAmount currencyCode="%s">%01.2F</BalanceAmount>',
            $this->getCurrencyCode(),
            $this->getBalanceAmount()
        );
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
     * Whether the response should be used.
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getResponseCode(), $this->successResponseCodes, true);
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
