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

class PayPalDoAuthorizationReply implements IPayPalDoAuthorizationReply
{
    const SUCCESS = 'Success';

    use TTopLevelPayload, TOrderId, TPayPalPaymentInfo;

    /** @var string * */
    protected $responseCode;

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
            'orderId' => 'string(x:OrderId)',
            'paymentStatus' => 'string(x:AuthorizationInfo/x:PaymentStatus)',
            'pendingReason' => 'string(x:AuthorizationInfo/x:PendingReason)',
            'reasonCode' => 'string(x:AuthorizationInfo/x:ReasonCode)',
        ];
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
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeOrderId()
        . "<ResponseCode>{$this->xmlEncode($this->getResponseCode())}</ResponseCode>"
        . "<AuthorizationInfo>"
        . $this->serializePayPalPaymentInfo()
        . "</AuthorizationInfo>";
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
