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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class StoredValueCardPayment implements IStoredValueCardPayment
{
    use TPayload, TAmount, TCustomAttributeContainer, TCardPayment;

    const ROOT_NODE = 'StoredValueCard';

    /** @var DateTime */
    protected $createTimeStamp;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $pin;

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->extractionPaths = [
            'orderId' => 'string(x:PaymentContext/x:PaymentSessionId)',
            'tenderType' => 'string(x:PaymentContext/x:TenderType)',
            'accountUniqueId' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
            'paymentRequestId' => 'string(x:PaymentRequestId)',
        ];
        $this->optionalExtractionPaths = [
            'amount' => 'x:Amount',
            'pin' => 'x:Pin',
        ];
        $this->booleanExtractionPaths = [
            'isMockPayment' => 'string(@isMockPayment)',
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)'
        ];
        $this->subpayloadExtractionPaths = [
            'customAttributes' => 'x:CustomAttributes',
        ];

        $this->customAttributes = $this->buildPayloadForInterface(self::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE);
    }

    public function getCreateTimeStamp()
    {
        return $this->createTimeStamp;
    }

    public function setCreateTimeStamp(DateTime $createTimeStamp)
    {
        $this->createTimeStamp = $createTimeStamp;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function setPin($pin)
    {
        $this->pin = $this->cleanString($pin, 22);
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializePaymentContext()
            . $this->serializePaymentRequestId()
            . "<CreateTimeStamp>{$this->getCreateTimeStamp()->format('c')}</CreateTimeStamp>"
            . $this->serializeOptionalXmlEncodedValue('Pin', $this->getPin())
            . $this->serializeAmount('Amount', $this->getAmount())
            . $this->getCustomAttributes()->serialize();
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $createTimestampValue = $xpath->evaluate('string(x:CreateTimeStamp)');
        $this->createTimeStamp = $createTimestampValue ? new DateTime($createTimestampValue) : null;
        return $this;
    }

    protected function getRootAttributes()
    {
        $isMockPayment = $this->getIsMockPayment();
        return !is_null($isMockPayment)
            ? ['isMockPayment' => $this->convertBooleanToString($isMockPayment)]
            : [];
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
