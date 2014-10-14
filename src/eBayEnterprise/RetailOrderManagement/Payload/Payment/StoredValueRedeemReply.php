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
 * Class StoredValueRedeemReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueRedeemReply implements IStoredValueRedeemReply
{
    /** @var string **/
    protected $pin;
    /** @var float **/
    protected $amountRedeemed;
    /** @var string **/
    protected $amountRedeemedCurrencyCode;
    /** @var float **/
    protected $balanceAmount;
    /** @var string **/
    protected $balanceAmountCurrencyCode;
    /** @var string **/
    protected $responseCode;
    /** @var string **/
    protected $cardNumber;
    /** @var bool */
    protected $panIsToken;
    /** @var  string */
    protected $orderId;
    /** @var array */
    protected $extractionPaths = [
        'orderId' => 'string(x:PaymentContext/x:OrderId)',
        'cardNumber' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
        'responseCode' => 'string(x:ResponseCode)',
        'amountRedeemed' => 'number(x:AmountRedeemed)',
        'amountRedeemedCurrencyCode' => 'string(x:AmountRedeemed/@currencyCode)',
        'balanceAmount' => 'number(x:BalanceAmount)',
        'balanceAmountCurrencyCode' => 'string(x:BalanceAmount/@currencyCode)',
    ];
    /** @var array property/XPath pairs that take boolean values*/
    protected $booleanXPaths = [
        'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)',
    ];
    protected $optionalExtractionPaths = [];

    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;

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

    public function getAmountRedeemed()
    {
        return $this->amountRedeemed;
    }

    public function setAmountRedeemed($amount)
    {
        if (is_float($amount)) {
            $this->amountRedeemed = round($amount, 2, PHP_ROUND_HALF_UP);
        } else {
            $this->amountRedeemed = null;
        }
        return $this;
    }

    public function getAmountRedeemedCurrencyCode()
    {
        return $this->amountRedeemedCurrencyCode;
    }

    public function setAmountRedeemedCurrencyCode($code)
    {
        $value = null;

        $cleaned = $this->cleanString($code, 3);
        if ($cleaned !== null) {
            if (!strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->amountRedeemedCurrencyCode = $value;

        return $this;
    }

    public function getBalanceAmount()
    {
        return $this->balanceAmount;
    }

    public function setBalanceAmount($amount)
    {
        if (is_float($amount)) {
            $this->balanceAmount = round($amount, 2, PHP_ROUND_HALF_UP);
        } else {
            $this->balanceAmount = null;
        }
        return $this;
    }

    public function getBalanceAmountCurrencyCode()
    {
        return $this->balanceAmountCurrencyCode;
    }

    public function setBalanceAmountCurrencyCode($code)
    {
        $value = null;

        $cleaned = $this->cleanString($code, 3);
        if ($cleaned !== null) {
            if (!strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->balanceAmountCurrencyCode = $value;

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
        . $this->serializeResponseCode()
        . $this->serializeAmounts('AmountRedeemed')
        . $this->serializeAmounts('BalanceAmount');
    }

    /**
     * Build the response code node
     * @return string
     */
    protected function serializeResponseCode()
    {
        return "<ResponseCode>{$this->getResponseCode()}</ResponseCode>";
    }

    /**
     * Build the Pin node
     *
     * @return string
     */
    protected function serializePin()
    {
        return "<Pin>{$this->getPin()}</Pin>";
    }

    /**
     * Build the Amount node
     * @param string $amountType either 'AmountRedeemed' or 'BalanceAmount'
     * @return string
     */
    protected function serializeAmounts($amountType)
    {
        $getVal = "get{$amountType}";
        $getCurCode = "{$getVal}CurrencyCode";
        return sprintf(
            '<%s currencyCode="%s">%1.02F</%1$s>',
            $amountType,
            $this->{$getCurCode}(),
            $this->{$getVal}()
        );
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
     * Indicates if the Payment Account Number (PAN) is the actual number, or a representation of the number.
     *
     * @return bool true if the PAN is a token, false if it's the actual number
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
        $this->panIsToken = is_bool($isToken) ? $isToken : null;
        return $this;
    }

    /**
     * @param string $ccNum
     * @return self
     */
    public function setCardNumber($ccNum)
    {
        $this->cardNumber = $this->cleanString($ccNum, 22);
        return $this;
    }

    /**
     * @param string $orderId
     * @return self
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $this->cleanString($orderId, 20);
        return $this;
    }
    /**
     * Either a tokenized or plain credit card number.
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
     * A unique identifier for the order
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     *
     * xsd restrictions: 1-20 characters
     * @return string
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCodeRedeemed()
    {
        return $this->amountRedeemedCurrencyCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCodeRedeemed($code)
    {
        $this->amountRedeemedCurrencyCode = $code;
    }

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getBalanceCurrencyCode()
    {
        return $this->balanceAmountCurrencyCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setBalanceCurrencyCode($code)
    {
        $this->balanceAmountCurrencyCode = $code;
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
}