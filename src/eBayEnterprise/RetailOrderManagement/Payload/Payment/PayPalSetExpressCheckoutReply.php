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

use eBayEnterprise\RetailOrderManagement\Payload;

class PayPalSetExpressCheckoutReply implements IPayPalSetExpressCheckoutReply
{
    use TOrderId;
    use TPayPalToken;
    use TPayPalValidators;
    use TStrings;

    const SUCCESS_MESSAGE = 'Success';

    /** @var string **/
    protected $responseCode;
    /** @var string **/
    protected $errorMessage;
    /** @var array */
    protected $extractionPaths = [
        'orderId' => 'string(x:OrderId)',
        'responseCode' => 'string(x:ResponseCode)',
        'token' => 'string(x:Token)',
    ];
    /** @var array */
    protected $optionalExtractionPaths = [
        'errorMessage' => 'x:ErrorMessage',
    ];
    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;
    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }
    /**
     * Response code like Success, Failure etc
     *
     * @return string
     */
    public function getResponseCode()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalSetExpressCheckoutReply
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
     * The description of error like "10413:The totals of the cart item amounts do not match order amounts".
     *
     * @return string
     */
    public function getErrorMessage()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalSetExpressCheckoutReply
        return $this->errorMessage;
    }

    /**
     * @param string
     * @return self
     */
    public function setErrorMessage($message)
    {
        $this->errorMessage = $message;
        return $this;
    }

    /**
     * Should downstream systems consider this reply a success?
     *
     * @return bool
     */
    public function isSuccess()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalSetExpressCheckoutReply
        return ($this->getResponseCode() === self::SUCCESS_MESSAGE);
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
        return $this->serializeOrderId()
            . $this->serializeResponseCode()
            . ($this->getToken() ? $this->serializeToken() : '')
            . $this->serializeErrorMessage();
    }
    /**
     * Serialize the response code.
     * @return string
     */
    protected function serializeResponseCode()
    {
        return '<ResponseCode>'.$this->getResponseCode().'</ResponseCode>';
    }
    /**
     * Serialize the error message.
     * @return string
     */
    protected function serializeErrorMessage()
    {
        return $this->getErrorMessage() ? '<ErrorMessage>'.$this->getErrorMessage().'</ErrorMessage>' : '';
    }
    /**
     * Load the payload XML into a DOMDocument
     * @param  string $xmlString
     * @return \DOMDocument
     */
    protected function getPayloadAsDoc($xmlString)
    {
        $dom = new \DOMDocument();
        $dom->loadXML($xmlString);
        return $dom;
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
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $string
     * @return self
     */
    public function deserialize($serializedPayload)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\IPayload
        $this->schemaValidate($serializedPayload);

        $xpath = $this->getPayloadAsXPath($serializedPayload);
        foreach ($this->extractionPaths as $property => $path) {
            $this->$property = $xpath->evaluate($path);
        }
        // When optional nodes are not included in the serialized data,
        // they should not be set in the payload.
        foreach ($this->optionalExtractionPaths as $property => $path) {
            $foundNode = $xpath->query($path)->item(0);
            if ($foundNode) {
                $this->$property = $foundNode->nodeValue;
            }
        }
        // payload is only valid of the unserialized data is also valid
        $this->validate();

        return $this;
    }
}

