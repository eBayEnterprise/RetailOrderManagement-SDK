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
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class StoredValueBalanceReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueBalanceReply implements IStoredValueBalanceReply
{
    use TTopLevelPayload, TPaymentAccountUniqueId;
    /** @var float */
    protected $balanceAmount;
    /** @var string */
    protected $currencyCode;
    /** @var string */
    protected $responseCode;
    /** @var array response codes that are considered a success */
    protected $successResponseCodes = ['Success'];

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'cardNumber' => 'string(x:EncryptedPaymentAccountUniqueId|x:PaymentAccountUniqueId)',
            'balanceAmount' => 'number(x:BalanceAmount)',
            'currencyCode' => 'string(x:BalanceAmount/@currencyCode)',
            'responseCode' => 'string(x:ResponseCode)',
        ];
        /** @var array property/XPath pairs that take boolean values */
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentAccountUniqueId/@isToken)'
        ];
    }

    /**
     * Whether the response should be used.
     * @return bool
     */
    public function isSuccessful()
    {
        return in_array($this->getResponseCode(), $this->successResponseCodes, true);
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
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
        return "<ResponseCode>{$this->xmlEncode($this->getResponseCode())}</ResponseCode>";
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
     * Create an XML string representing the amount authorized.
     * @return string
     */
    protected function serializeAmount()
    {
        return sprintf(
            '<BalanceAmount currencyCode="%s">%01.2F</BalanceAmount>',
            $this->xmlEncode($this->getCurrencyCode()),
            $this->getBalanceAmount()
        );
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
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

    public function getBalanceAmount()
    {
        return $this->balanceAmount;
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
