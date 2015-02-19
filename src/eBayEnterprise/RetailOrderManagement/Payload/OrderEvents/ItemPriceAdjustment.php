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

class ItemPriceAdjustment implements IItemPriceAdjustment
{
    use TPayload, TAmount;

    /** @var string */
    protected $modificationType;
    /** @var string */
    protected $adjustmentCategory;
    /** @var bool */
    protected $isCredit;
    /** @var float */
    protected $amount;

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
            'modificationType' => 'string(@ModificationType)',
            'adjustmentCategory' => 'string(@AdjustmentCategory)',
            'isCredit' => 'string(@IsCredit)',
            'amount' => 'number(@Amount)',
        ];
    }

    /**
     * @return string $modificationType
     */
    public function getModificationType()
    {
        return $this->modificationType;
    }

    /**
     * @param string $modificationType
     */
    public function setModificationType($modificationType)
    {
        $this->modificationType = $modificationType;
        return $this;
    }

    /**
     * @return string $adjustmentCategory
     */
    public function getAdjustmentCategory()
    {
        return $this->adjustmentCategory;
    }

    /**
     * @param string $adjustmentCategory
     */
    public function setAdjustmentCategory($adjustmentCategory)
    {
        $this->adjustmentCategory = $adjustmentCategory;
        return $this;
    }

    /**
     * @return bool $isCredit
     */
    public function getIsCredit()
    {
        return $this->isCredit;
    }

    /**
     * @param bool $isCredit
     */
    public function setIsCredit($isCredit)
    {
        $this->isCredit = $isCredit;
        return $this;
    }

    /**
     * @return float $amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    /**
     * nothing special to serialize
     * @return string
     */
    protected function serializeContents()
    {
        return '';
    }

    /**
     * take advantage of when deserializeExtra gets called to
     * sanitize the amount field.
     * @param  string $serializedData
     * @return self
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function deserializeExtra($serializedData)
    {
        $this->amount = $this->sanitizeAmount($this->amount);
        return $this;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [
            'ModificationType' => $this->getModificationType(),
            'AdjustmentCategory' => $this->getAdjustmentCategory(),
            'IsCredit' => $this->getIsCredit(),
            'Amount' => $this->formatAmount($this->getAmount()),
        ];
    }
}
