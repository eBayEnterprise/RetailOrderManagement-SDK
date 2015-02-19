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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Payment implements IPayment
{
    use TPayload, TAmount;

    /** @var string */
    protected $description;
    /** @var string */
    protected $tenderType;
    /** @var string */
    protected $maskedAccount;
    /** @var float */
    protected $amount;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator unused, kept to allow parent payload to be passed
     * @param IPayloadMap unused, kept to allow parent payload to be passed
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
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'description' => 'string(x:PaymentDescription)',
            'tenderType' => 'string(x:PaymentTenderType)',
            'maskedAccount' => 'string(x:PaymentMaskedAccount)',
            'amount' => 'number(x:PaymentAmount)',
        ];
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getTenderType()
    {
        return $this->tenderType;
    }

    public function setTenderType($tenderType)
    {
        $this->tenderType = $tenderType;
        return $this;
    }

    public function getMaskedAccount()
    {
        return $this->maskedAccount;
    }

    public function setMaskedAccount($maskedAccount)
    {
        $this->maskedAccount = $maskedAccount;
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

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return sprintf(
            '<PaymentDescription>%s</PaymentDescription><PaymentTenderType>%s</PaymentTenderType>' .
            '<PaymentMaskedAccount>%s</PaymentMaskedAccount>%s',
            $this->getDescription(),
            $this->getTenderType(),
            $this->getMaskedAccount(),
            $this->serializeAmount('PaymentAmount', $this->getAmount())
        );
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
