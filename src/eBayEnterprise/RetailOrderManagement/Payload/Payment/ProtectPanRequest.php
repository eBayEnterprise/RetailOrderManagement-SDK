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

use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TPayloadLogger;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ProtectPanRequest implements IProtectPanRequest
{
    use TTopLevelPayload, TPayloadLogger;

    /** @var string */
    protected $paymentAccountNumber;
    /** @var string */
    protected $tenderClass;
    /** @var array */
    protected $tenderClassEnum = [self::TENDER_CLASS_CC, self::TENDER_CLASS_PL_CC, self::TENDER_CLASS_SV,];

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
            'paymentAccountNumber' => 'string(x:PaymentAccountNumber)',
            'tenderClass' => 'string(x:TenderClass)',
        ];
    }


    public function getPaymentAccountNumber()
    {
        return $this->paymentAccountNumber;
    }

    public function setPaymentAccountNumber($paymentAccountNumber)
    {
        $this->paymentAccountNumber = $this->cleanString($paymentAccountNumber, 50);
        return $this;
    }

    public function getTenderClass()
    {
        return $this->tenderClass;
    }

    public function setTenderClass($tenderClass)
    {
        $isAllowed = in_array($tenderClass, $this->tenderClassEnum);
        $this->tenderClass = $isAllowed ? $tenderClass : null;
        if (!$isAllowed) {
            $logData = ['tender_class' => $tenderClass];
            $this->logger->warning(
                'Tender Class "{tender_class}" is not allowed.',
                $this->getLogContextData(__CLASS__, $logData)
            );
        }
        return $this;
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeRequiredValue('PaymentAccountNumber', $this->xmlEncode($this->getPaymentAccountNumber()))
            . $this->serializeRequiredValue('TenderClass', $this->xmlEncode($this->getTenderClass()));
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
}
