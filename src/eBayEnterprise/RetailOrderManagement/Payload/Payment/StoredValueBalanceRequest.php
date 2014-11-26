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
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

/**
 * Class StoredValueBalanceRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueBalanceRequest implements IStoredValueBalanceRequest
{
    use TPayload, TPaymentAccountUniqueId;

    /** @var string $requestId */
    protected $requestId;
    /** @var bool $panIsToken Indicates if the card number is the actual number, or a representation of the number. */
    protected $pin;
    protected $currencyCode;
    /**
     * @param IValidatorIterator $validators Payload object validators
     * @param ISchemaValidator $schemaValidator Serialized object schema validator
     */
    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'cardNumber' => 'string(x:EncryptedPaymentAccountUniqueId|x:PaymentAccountUniqueId)',
            'currencyCode' => 'string(x:CurrencyCode)',
        ];
        $this->optionalExtractionPaths = [
            'pin' => 'x:Pin',
        ];
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentAccountUniqueId/@isToken)'
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
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
    /**
     * @param string $requestId
     * @return self
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * The personal identification number or code associated with a giftcard
     * account unique id
     *
     * xsd note: 1-8 characters, exclude if empty
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
        return $this;
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
            static::ROOT_NODE,
            self::XML_NS,
            $this->serializeContents()
        );
        $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
        $this->schemaValidate($canonicalXml);
        return $canonicalXml;
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentAccountUniqueId()
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

    // all methods below should be refactored as they are literal copies
    // from somewhere else

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . static::XSD;
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
