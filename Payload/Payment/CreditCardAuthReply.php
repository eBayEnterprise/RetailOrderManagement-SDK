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

class CreditCardAuthReply implements ICreditCardAuthReply
{
    // XML related values - document root node, XMLNS and name of the xsd schema file
    const ROOT_NODE = 'CreditCardAuthReply';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const PAYLOAD_SCHEMA = 'Payment-Service-CreditCardAuth-1.0.xsd';
    // API response codes relevent to payload success/failure and OMS response code
    const AUTHORIZATION_APPROVED = 'AP01';
    const AUTHORIZATION_TIMEOUT_PAYMENT_PROVIDER = 'TO01';
    const AUTHORIZATION_TIMEOUT_CARD_PROCESSOR = 'NR01';
    // response codes that are to be reported to the OMS
    const APPROVED_RESPONSE_CODE = 'APPROVED';
    const TIMEOUT_RESPONSE_CODE = 'TIMEOUT';

    /** @var string **/
    protected $orderId;
    /** @var string **/
    protected $paymentAccountUniqueId;
    /** @var bool **/
    protected $panIsToken;
    /** @var string **/
    protected $authorizationResponseCode;
    /** @var string **/
    protected $bankAuthorizationCode;
    /** @var string **/
    protected $cvv2ResponseCode;
    /** @var string **/
    protected $avsResponseCode;
    /** @var string */
    protected $phoneResponseCode;
    /** @var string */
    protected $nameResponseCode;
    /** @var string */
    protected $emailResponseCode;
    /** @var float **/
    protected $amountAuthorized;
    /** @var string **/
    protected $currencyCode;
    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;
    /** @var array XPath expressions to extract required data from the serialized payload (XML) */
    protected $extractionPaths = array(
        'orderId' => 'string(x:PaymentContext/x:OrderId)',
        'paymentAccountUniqueId' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
        'authorizationResponseCode' => 'string(x:AuthorizationResponseCode)',
        'bankAuthorizationCode' => 'string(x:BankAuthorizationCode)',
        'cvv2ResponseCode' => 'string(x:CVV2ResponseCode)',
        'avsResponseCode' => 'string(x:AVSResponseCode)',
        'amountAuthorized' => 'number(x:AmountAuthorized)',
        'currencyCode' => 'string(x:AmountAuthorized/@currencyCode)',
    );
    /** @var array property/XPath pairs that take boolean values*/
    protected $booleanXPaths = array(
        'panIsToken' => 'x:PaymentContext/x:PaymentAccountUniqueId/@isToken'
    );
    /** @var array XPath expressions to match optional nodes in the serialized payload (XML) */
    protected $optionalExtractionPaths = array(
        'phoneResponseCode' => 'x:PhoneResponseCode',
        'nameResponseCode' => 'x:NameResponseCode',
        'emailResponseCode' => 'x:EmailResponseCode',
    );
    /** @var array Mapping of reply authorization response code to OMS response code */
    protected $responseCodeMap = array(
        self::AUTHORIZATION_APPROVED => self::APPROVED_RESPONSE_CODE,
        self::AUTHORIZATION_TIMEOUT_PAYMENT_PROVIDER => self::TIMEOUT_RESPONSE_CODE,
        self::AUTHORIZATION_TIMEOUT_CARD_PROCESSOR => self::TIMEOUT_RESPONSE_CODE,
    );
    /** @var string[] AVS response codes that should be rejected */
    protected $invalidAvsCodes = array('N', 'AW');
    /** @var string[] CVV response codes that should be rejected */
    protected $invalidCvvCodes = array('N');

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function getPaymentAccountUniqueId()
    {
        return $this->paymentAccountUniqueId;
    }

    public function getPanIsToken()
    {
        return $this->panIsToken;
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

    public function getIsAuthSuccessful()
    {
        $authResponseCode = $this->getAuthorizationResponseCode();
        return (
            $authResponseCode === self::AUTHORIZATION_APPROVED
            && !in_array($this->getCVV2ResponseCode(), $this->invalidCvvCodes)
            && !in_array($this->getAVSResponseCode(), $this->invalidAvsCodes)
        ) || (
            $authResponseCode === self::AUTHORIZATION_TIMEOUT_PAYMENT_PROVIDER
            || $authResponseCode === self::AUTHORIZATION_TIMEOUT_CARD_PROCESSOR
        );
    }

    public function getIsAuthAcceptable()
    {
        // if there is a response code accpetable by the OMS self::getResponseCode
        // doesn't return null, then the reply is acceptable
        return !is_null($this->getResponseCode());
    }

    public function getResponseCode()
    {
        $replyAuthCode = $this->getAuthorizationResponseCode();
        return isset($this->responseCodeMap[$replyAuthCode]) ? $this->responseCodeMap[$replyAuthCode] : null;
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
        $this->evaluateBooleanXPaths($xpath, $this->booleanXPaths);

        // payload is only valid of the unserialized data is also valid
        $this->validate();
        return $this;
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
        return __DIR__ . '/schema/' . self::PAYLOAD_SCHEMA;
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
     * Create an XML string representing the PaymentContext nodes
     * @return string
     */
    protected function serializePaymentContext()
    {
        return sprintf(
            '<PaymentContext><OrderId>%s</OrderId><PaymentAccountUniqueId isToken="%s">%s</PaymentAccountUniqueId></PaymentContext>',
            $this->getOrderId(),
            $this->getPanIsToken() ? 'true' : 'false',
            $this->getPaymentAccountUniqueId()
        );
    }

    /**
     * Create an XML string representing the various response codes, e.g.
     * AuthorizationResponseCode, BankAuthorizationCode, CVV2ResponseCode, etc.
     * @return string
     */
    protected function serializeResponseCodes()
    {
        return sprintf(
            '<AuthorizationResponseCode>%s</AuthorizationResponseCode><BankAuthorizationCode>%s</BankAuthorizationCode><CVV2ResponseCode>%s</CVV2ResponseCode><AVSResponseCode>%s</AVSResponseCode>',
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
     * Utility function to convert "true" => true and "false" => false
     * for attributes and elements in our XML that are used to
     * store boolean values
     *
     * @param \DOMXPath $domXPath
     * @param array $xPaths
     */
    protected function evaluateBooleanXPaths(\DOMXPath $domXPath, $xPaths)
    {
        if (!is_array($xPaths)) {
            return;
        }

        foreach ($xPaths as $property => $xPath) {
            $this->$property = null;
            $nodes = $domXPath->query($xPath);
            if ($nodes->length > 0) {
                $value = $nodes->item(0)->nodeValue;
                $this->$property = (($value === 'true') || ($value === '1')) ? true : false;
            }
        }
    }
}
