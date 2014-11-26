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
 * Class CreditCardAuthReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class CreditCardAuthReply implements ICreditCardAuthReply
{
    use TPayload, TPaymentContext;
    /** @var string */
    protected $authorizationResponseCode;
    /** @var string */
    protected $bankAuthorizationCode;
    /** @var string */
    protected $cvv2ResponseCode;
    /** @var string */
    protected $avsResponseCode;
    /** @var string */
    protected $phoneResponseCode;
    /** @var string */
    protected $nameResponseCode;
    /** @var string */
    protected $emailResponseCode;
    /** @var float */
    protected $amountAuthorized;
    /** @var string */
    protected $currencyCode;
    /** @var array Mapping of reply authorization response code to OMS response code */
    protected $responseCodeMap = [
        self::AUTHORIZATION_APPROVED => self::APPROVED_RESPONSE_CODE,
        self::AUTHORIZATION_TIMEOUT_PAYMENT_PROVIDER => self::TIMEOUT_RESPONSE_CODE,
        self::AUTHORIZATION_TIMEOUT_CARD_PROCESSOR => self::TIMEOUT_RESPONSE_CODE,
    ];
    /** @var string[] AVS response codes that should be rejected */
    protected $invalidAvsCodes = ['N', 'AW'];
    /** @var string[] CVV response codes that should be rejected */
    protected $invalidCvvCodes = ['N'];

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'orderId' => 'string(x:PaymentContext/x:OrderId)',
            'cardNumber' =>
                'string(x:PaymentContext/x:EncryptedPaymentAccountUniqueId|x:PaymentContext/x:PaymentAccountUniqueId)',
            'authorizationResponseCode' => 'string(x:AuthorizationResponseCode)',
            'bankAuthorizationCode' => 'string(x:BankAuthorizationCode)',
            'cvv2ResponseCode' => 'string(x:CVV2ResponseCode)',
            'avsResponseCode' => 'string(x:AVSResponseCode)',
            'amountAuthorized' => 'number(x:AmountAuthorized)',
            'currencyCode' => 'string(x:AmountAuthorized/@currencyCode)',
            'isEncrypted' => 'boolean(x:PaymentContext/x:EncryptedPaymentAccountUniqueId)',
        ];
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)'
        ];
        $this->optionalExtractionPaths = [
            'phoneResponseCode' => 'x:PhoneResponseCode',
            'nameResponseCode' => 'x:NameResponseCode',
            'emailResponseCode' => 'x:EmailResponseCode',
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    public function getAuthorizationResponseCode()
    {
        return $this->authorizationResponseCode;
    }

    public function getBankAuthorizationCode()
    {
        return $this->bankAuthorizationCode;
    }

    public function getCVV2ResponseCode()
    {
        return $this->cvv2ResponseCode;
    }

    public function getAVSResponseCode()
    {
        return $this->avsResponseCode;
    }

    public function getPhoneResponseCode()
    {
        return $this->phoneResponseCode;
    }

    public function getNameResponseCode()
    {
        return $this->nameResponseCode;
    }

    public function getEmailResponseCode()
    {
        return $this->emailResponseCode;
    }

    public function getAmountAuthorized()
    {
        return $this->amountAuthorized;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function getIsAVSSuccessful()
    {
        return !in_array($this->getAVSResponseCode(), $this->invalidAvsCodes);
    }

    public function getIsAVSCorrectionRequired()
    {
        return $this->getIsAuthApproved() && !$this->getIsAVSSuccessful();
    }

    public function getIsCVV2Successful($value = '')
    {
        return !in_array($this->getCVV2ResponseCode(), $this->invalidCvvCodes);
    }

    public function getIsCVV2CorrectionRequired()
    {
        return $this->getIsAuthApproved() && !$this->getIsCVV2Successful();
    }

    public function getIsAuthApproved()
    {
        return $this->getAuthorizationResponseCode() === self::AUTHORIZATION_APPROVED;
    }

    public function getIsAuthTimeout()
    {
        $responseCode = $this->getAuthorizationResponseCode();
        return $responseCode === self::AUTHORIZATION_TIMEOUT_PAYMENT_PROVIDER
            || $responseCode === self::AUTHORIZATION_TIMEOUT_CARD_PROCESSOR;
    }

    public function getIsAuthSuccessful()
    {
        return ($this->getIsAuthApproved() && $this->getIsAVSSuccessful() && $this->getIsCVV2Successful())
            || ($this->getIsAuthTimeout());
    }

    public function getIsAuthAcceptable()
    {
        // If there is a response code acceptable by the OMS self::getResponseCode
        // doesn't return null, then the reply is acceptable
        return !is_null($this->getResponseCode());
    }

    public function getResponseCode()
    {
        $responseCode = $this->getAuthorizationResponseCode();
        return isset($this->responseCodeMap[$responseCode]) ? $this->responseCodeMap[$responseCode] : null;
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

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentContext()
            . $this->serializeResponseCodes()
            . $this->serializeAdditionalResponseCodes()
            . $this->serializeAmount();
    }

    /**
     * Create an XML string representing the various response codes, e.g.
     * AuthorizationResponseCode, BankAuthorizationCode, CVV2ResponseCode, etc.
     * @return string
     */
    protected function serializeResponseCodes()
    {
        $template = '<AuthorizationResponseCode>%s</AuthorizationResponseCode>'
            . '<BankAuthorizationCode>%s</BankAuthorizationCode>'
            . '<CVV2ResponseCode>%s</CVV2ResponseCode>'
            . '<AVSResponseCode>%s</AVSResponseCode>';
        return sprintf(
            $template,
            $this->getAuthorizationResponseCode(),
            $this->getBankAuthorizationCode(),
            $this->getCVV2ResponseCode(),
            $this->getAVSResponseCode()
        );
    }

    /**
     * Create an XML string representing any of the optional response codes,
     * e.g. EmailResponseCode, PhoneResponseCode, etc.
     * @return string
     */
    protected function serializeAdditionalResponseCodes()
    {
        $phoneResponseCode = $this->getPhoneResponseCode();
        $nameResponseCode = $this->getNameResponseCode();
        $emailResponseCode = $this->getEmailResponseCode();
        return ($phoneResponseCode ? "<PhoneResponseCode>{$phoneResponseCode}</PhoneResponseCode>" : '')
            . ($nameResponseCode ? "<NameResponseCode>{$nameResponseCode}</NameResponseCode>" : '')
            . ($emailResponseCode ? "<EmailResponseCode>{$emailResponseCode}</EmailResponseCode>" : '');
    }

    /**
     * Create an XML string representing the amount authorized.
     * @return string
     */
    protected function serializeAmount()
    {
        return sprintf(
            '<AmountAuthorized currencyCode="%s">%01.2F</AmountAuthorized>',
            $this->getCurrencyCode(),
            $this->getAmountAuthorized()
        );
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
