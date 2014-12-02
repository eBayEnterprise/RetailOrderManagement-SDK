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

class PayPalDoAuthorizationReply implements IPayPalDoAuthorizationReply
{
    const SUCCESS = 'Success';

    use Payload\TPayload, TOrderId;

    /** @var string **/
    protected $responseCode;
    /** @var string **/
    protected $paymentStatus;
    /** @var string **/
    protected $pendingReason;
    /** @var string **/
    protected $reasonCode;

    /**
     * @param Payload\IValidatorIterator $validators
     * @param Payload\ISchemaValidator $schemaValidator
     */
    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'responseCode' => 'string(x:ResponseCode)',
            'orderId' => 'string(x:OrderId)',
            'paymentStatus' => 'string(x:AuthorizationInfo/x:PaymentStatus)',
            'pendingReason' => 'string(x:AuthorizationInfo/x:PendingReason)',
            'reasonCode' => 'string(x:AuthorizationInfo/x:ReasonCode)',
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
        return ($this->getResponseCode() === static::SUCCESS);
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
     * Return the string form of the payload data for transmission.
     * Validation is implied.
     *
     * @throws Payload\Exception\InvalidPayload
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeOrderId()
        . "<ResponseCode>{$this->getResponseCode()}</ResponseCode>"
        . "<AuthorizationInfo>"
        . "<PaymentStatus>{$this->getPaymentStatus()}</PaymentStatus>"
        . "<PendingReason>{$this->getPendingReason()}</PendingReason>"
        . "<ReasonCode>{$this->getReasonCode()}</ReasonCode>"
        . "</AuthorizationInfo>";
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
