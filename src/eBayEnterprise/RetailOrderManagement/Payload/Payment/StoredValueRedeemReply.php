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
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

/**
 * Class StoredValueRedeemReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueRedeemReply implements IStoredValueRedeemReply
{
    use TPayload, TPaymentContext;

    /** @var string */
    protected $pin;
    /** @var float */
    protected $amountRedeemed;
    /** @var string */
    protected $amountRedeemedCurrencyCode;
    /** @var float */
    protected $balanceAmount;
    /** @var string */
    protected $balanceAmountCurrencyCode;
    /** @var string */
    protected $responseCode;
    /** @var array response codes that are considered a success */
    protected $successResponseCodes = ['Success'];

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'orderId' => 'string(x:PaymentContext/x:OrderId)',
            'cardNumber' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
            'responseCode' => 'string(x:ResponseCode)',
            'amountRedeemed' => 'number(x:AmountRedeemed)',
            'amountRedeemedCurrencyCode' => 'string(x:AmountRedeemed/@currencyCode)',
            'balanceAmount' => 'number(x:BalanceAmount)',
            'balanceAmountCurrencyCode' => 'string(x:BalanceAmount/@currencyCode)',
        ];
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)',
        ];
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
        return $this;
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
        return $this;
    }
    /**
     * Whether the gift card was successfully redeemed.
     * @return bool
     */
    public function wasRedeemed()
    {
        return in_array($this->getResponseCode(), $this->successResponseCodes, true);
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
        return __DIR__ . '/schema/' . static::XSD;
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
