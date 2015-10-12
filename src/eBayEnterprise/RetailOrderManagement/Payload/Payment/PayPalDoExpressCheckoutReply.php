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

class PayPalDoExpressCheckoutReply implements IPayPalDoExpressCheckoutReply
{
    use TTopLevelPayload, TOrderId, TPayPalPaymentInfo;

    const SUCCESS = 'Success';

    /** @var string * */
    protected $responseCode;
    /** @var string * */
    protected $transactionId;
    /** @var string * */
    protected $errorMessage;

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
            'responseCode' => 'string(x:ResponseCode)',
            'transactionId' => 'string(x:TransactionID)',
            'errorMessage' => 'string(x:ErrorMessage)',
            'orderId' => 'string(x:OrderId)',
            'paymentStatus' => 'string(x:PaymentInfo/x:PaymentStatus)',
            'pendingReason' => 'string(x:PaymentInfo/x:PendingReason)',
            'reasonCode' => 'string(x:PaymentInfo/x:ReasonCode)',
        ];
    }

    /**
     * The description of error like "10413:The totals of the cart item amounts do not match order amounts".
     *
     * @return string
     */
    public function getErrorMessage()
    {
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
        return ($this->getResponseCode() === self::SUCCESS);
    }

    /**
     * Response code like Success, Failure etc
     *
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
     * Returns the bulk of the XML required to make a reply
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeOrderId()
        . "<ResponseCode>{$this->xmlEncode($this->getResponseCode())}</ResponseCode>"
        . "<TransactionID>{$this->xmlEncode($this->getTransactionId())}</TransactionID>"
        . "<PaymentInfo>"
        . $this->serializePayPalPaymentInfo()
        . "</PaymentInfo>";
    }

    /**
     * A transaction identification number.
     * Character length and limits: 19 single-byte characters maximum
     *
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string
     * @return self
     */
    public function setTransactionId($id)
    {
        $this->transactionId = $id;
        return $this;
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
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
