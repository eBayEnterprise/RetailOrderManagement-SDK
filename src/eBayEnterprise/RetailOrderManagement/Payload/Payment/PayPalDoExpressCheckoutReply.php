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

class PayPalDoExpressCheckoutReply implements IPayPalDoExpressCheckoutReply
{
    use Payload\TTopLevelPayload, TOrderId;

    const SUCCESS = 'Success';

    /** @var string * */
    protected $responseCode;
    /** @var string * */
    protected $transactionId;
    /** @var string * */
    protected $errorMessage;
    /** @var string * */
    protected $paymentStatus;
    /** @var string * */
    protected $pendingReason;
    /** @var string * */
    protected $reasonCode;

    /**
     * @param Payload\IValidatorIterator $validators
     * @param Payload\ISchemaValidator $schemaValidator
     */
    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'responseCode' => 'string(x:ResponseCode)',
            'transactionId' => 'string(x:TransactionID)',
            'errorMessage' => 'string(x:ErrorMessage)',
            'orderId' => 'string(x:OrderId)',
            'paymentStatus' => 'string(x:PaymentInfo/x:PaymentStatus)',
            'pendingReason' => 'string(x:PaymentInfo/x:PendingReason)',
            'reasonCode' => 'string(x:PaymentInfo/x:ReasonCode)',
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
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
        . "<ResponseCode>{$this->getResponseCode()}</ResponseCode>"
        . "<TransactionID>{$this->getTransactionId()}</TransactionID>"
        . "<PaymentInfo>"
        . "<PaymentStatus>{$this->getPaymentStatus()}</PaymentStatus>"
        . "<PendingReason>{$this->getPendingReason()}</PendingReason>"
        . "<ReasonCode>{$this->getReasonCode()}</ReasonCode>"
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
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getPaymentStatus()
    {
        return $this->paymentStatus;
    }

    /**
     * @param string
     * @return self
     */
    public function setPaymentStatus($status)
    {
        $this->paymentStatus = $status;
        return $this;
    }

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getPendingReason()
    {
        return $this->pendingReason;
    }

    /**
     * @param string
     * @return self
     */
    public function setPendingReason($reason)
    {
        $this->pendingReason = $reason;
        return $this;
    }

    /**
     * This value is passed through from the Order Management System. It is returned from a PayPal Get.
     * (However, this field is in the XSD for more than just Get.)
     *
     * @return string
     */
    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setReasonCode($code)
    {
        $this->reasonCode = $code;
        return $this;
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
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
