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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Charge implements ICharge
{
    use TPayload;

    /** @var string */
    protected $name;
    /** @var string */
    protected $category;
    /** @var bool */
    protected $isDiscount;
    /** @var bool */
    protected $isPromotion;
    /** @var string */
    protected $informational;
    /** @var float */
    protected $unitPrice;
    /** @var float */
    protected $linePrice;
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
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = $this->getNewPayloadFactory();
        $this->initOptionalExtractPaths();
    }

    /**
     * Initialize the protected class property array self::optionalExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initOptionalExtractPaths()
    {
        $this->optionalExtractionPaths = [
            'name' => '@name',
            'category' => '@category',
            'isDiscount' => '@isDiscount',
            'isPromotion' => '@isPromotion',
            'informational' => '@informational',
            'unitPrice' => '@unitPrice',
            'linePrice' => '@linePrice',
            'amount' => '@amount',
        ];
        return $this;
    }

    /**
     * @see ICharge::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @see ICharge::setName()
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @see ICharge::getCategory()
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @see ICharge::setCategory()
     * @codeCoverageIgnore
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @see ICharge::getIsDiscount()
     */
    public function getIsDiscount()
    {
        return $this->isDiscount;
    }

    /**
     * @see ICharge::setIsDiscount()
     * @codeCoverageIgnore
     */
    public function setIsDiscount($isDiscount)
    {
        $this->isDiscount = $isDiscount;
        return $this;
    }

    /**
     * @see ICharge::getIsPromotion()
     */
    public function getIsPromotion()
    {
        return $this->isPromotion;
    }

    /**
     * @see ICharge::setIsPromotion()
     * @codeCoverageIgnore
     */
    public function setIsPromotion($isPromotion)
    {
        $this->isPromotion = $isPromotion;
        return $this;
    }

    /**
     * @see ICharge::getInformational()
     */
    public function getInformational()
    {
        return $this->informational;
    }

    /**
     * @see ICharge::setInformational()
     * @codeCoverageIgnore
     */
    public function setInformational($informational)
    {
        $this->informational = $informational;
        return $this;
    }

    /**
     * @see ICharge::getUnitPrice()
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @see ICharge::setUnitPrice()
     * @codeCoverageIgnore
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @see ICharge::getLinePrice()
     */
    public function getLinePrice()
    {
        return $this->linePrice;
    }

    /**
     * @see ICharge::setLinePrice()
     * @codeCoverageIgnore
     */
    public function setLinePrice($linePrice)
    {
        $this->linePrice = $linePrice;
        return $this;
    }

    /**
     * @see ICharge::getAmount()
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @see ICharge::setAmount()
     * @codeCoverageIgnore
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return '';
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $name = $this->getName();
        $category = $this->getCategory();
        $isDiscount = $this->getIsDiscount();
        $isPromotion = $this->getIsPromotion();
        $informational = $this->getInformational();
        $unitPrice = $this->getUnitPrice();
        $linePrice = $this->getLinePrice();
        $amount = $this->getAmount();
        return array_merge(
            $name ? ['name' => $name] : [],
            $category ? ['category' => $category] : [],
            $isDiscount ? ['isDiscount' => $isDiscount] : [],
            $isPromotion ? ['isPromotion' => $isPromotion] : [],
            $informational ? ['informational' => $informational] : [],
            is_numeric($unitPrice) ? ['unitPrice' => $unitPrice] : [],
            is_numeric($linePrice) ? ['linePrice' => $linePrice] : [],
            is_numeric($amount) ? ['amount' => $amount] : []
        );
    }
}
