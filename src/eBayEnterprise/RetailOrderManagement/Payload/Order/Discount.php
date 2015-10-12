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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Discount implements IDiscount
{
    use TPayload, TAmount, TTaxContainer;

    const ROOT_NODE = 'Discount';

    /** @var int */
    protected $appliedCount;
    /** @var string */
    protected $id;
    /** @var string */
    protected $code;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $description;
    /** @var string */
    protected $effectType;

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->extractionPaths = [
            'id' => 'string(x:Id)',
            'amount' => 'number(x:Amount)',
        ];
        $this->optionalExtractionPaths = [
            'appliedCount' => '@appliedCount',
            'code' => 'x:Code',
            'description' => 'x:Description',
            'effectType' => 'x:EffectType',
            'taxClass' => 'x:TaxData/x:TaxClass',
        ];
        $this->subpayloadExtractionPaths = [
            'taxes' => 'x:TaxData/x:Taxes',
        ];

        $this->taxes = $this->buildPayloadForInterface(static::TAX_ITERABLE_INTERFACE);
    }

    public function getAppliedCount()
    {
        return $this->appliedCount;
    }

    public function setAppliedCount($appliedCount)
    {
        $this->appliedCount = $appliedCount;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
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

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getEffectType()
    {
        return $this->effectType;
    }

    public function setEffectType($effectType)
    {
        $this->effectType = $effectType;
        return $this;
    }

    protected function serializeContents()
    {
        return "<Id>{$this->xmlEncode($this->getId())}</Id>"
            . $this->serializeOptionalXmlEncodedValue('Code', $this->getCode())
            . $this->serializeAmount('Amount', $this->getAmount())
            . $this->serializeOptionalXmlEncodedValue('Description', $this->getDescription())
            . $this->serializeOptionalValue('EffectType', $this->getEffectType())
            . $this->serializeTaxData();
    }

    protected function getRootAttributes()
    {
        $appliedCount = $this->sanitizeAmount($this->getAppliedCount());
        return !is_null($appliedCount) ? ['appliedCount' => (int) $appliedCount] : [];
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
