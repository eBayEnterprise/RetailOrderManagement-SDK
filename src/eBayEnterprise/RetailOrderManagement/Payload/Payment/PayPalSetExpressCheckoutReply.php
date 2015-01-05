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
use eBayEnterprise\RetailOrderManagement\Payload\Exception;

class PayPalSetExpressCheckoutReply implements IPayPalSetExpressCheckoutReply
{
    use TOrderId, TToken, Payload\TTopLevelPayload;

    const SUCCESS_MESSAGE = 'Success';

    /** @var string * */
    protected $responseCode;
    /** @var string * */
    protected $errorMessage;

    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'orderId' => 'string(x:OrderId)',
            'responseCode' => 'string(x:ResponseCode)',
            'token' => 'string(x:Token)',
        ];
        $this->optionalExtractionPaths = [
            'errorMessage' => 'x:ErrorMessage',
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
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
        return '<ResponseCode>' . $this->getResponseCode() . '</ResponseCode>';
    }

    /**
     * Serialize the error message.
     * @return string
     */
    protected function serializeErrorMessage()
    {
        return $this->getErrorMessage() ? '<ErrorMessage>' . $this->getErrorMessage() . '</ErrorMessage>' : '';
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

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
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

    /**
     * Return the name of the xml root node.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }
}
