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
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;

/**
 * StoredValueRedeemVoidReply Payload
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 */
class StoredValueRedeemVoidReply implements IStoredValueRedeemVoidReply
{
    use TTopLevelPayload, TPaymentContext;

    protected $responseCode;
    /** @var array response codes that are considered a success */
    protected $successResponseCodes = ['Success'];

    /**
     * @param IValidatorIterator $validators Payload object validators
     * @param ISchemaValidator $schemaValidator Serialized object schema validator
     */
    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'orderId' => 'string(x:PaymentContext/x:OrderId)',
            'cardNumber' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
            'responseCode' => ' string(x:ResponseCode)',
        ];
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)'
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    /**
     * Whether the gift card redeem was successfully voided.
     * @return bool
     */
    public function wasVoided()
    {
        return in_array($this->getResponseCode(), $this->successResponseCodes, true);
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
     * Return the schema file path.
     *
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . static::XSD;
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
