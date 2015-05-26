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

class ChargeGroup implements IChargeGroup
{
    use TPayload;

    /** @var string */
    protected $name;
    /** @var string */
    protected $adjustmentCategory;
    /** @var IReferencedCharge */
    protected $referencedCharge;
    /** @var IAdjustmentCharge */
    protected $adjustmentCharge;

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
        $this->initOptionalExtractPaths()
            ->initSubPayloadExtractPaths()
            ->initSubPayloadProperties();
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
            'adjustmentCategory' => '@adjustmentCategory',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::subpayloadExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initSubPayloadExtractPaths()
    {
        $this->subpayloadExtractionPaths = [
            'referencedCharge' => 'x:ReferencedCharges',
            'adjustmentCharge' => 'x:AdjustmentCharges',
        ];
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setReferencedCharge($this->buildPayloadForInterface(
            static::REFERENCE_CHARGES_INTERFACE
        ));
        $this->setAdjustmentCharge($this->buildPayloadForInterface(
            static::ADJUSTMENT_CHARGES_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IChargeGroup::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @see IChargeGroup::setName()
     * @codeCoverageIgnore
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @see IChargeGroup::getAdjustmentCategory()
     */
    public function getAdjustmentCategory()
    {
        return $this->adjustmentCategory;
    }

    /**
     * @see IChargeGroup::setAdjustmentCategory()
     * @codeCoverageIgnore
     */
    public function setAdjustmentCategory($adjustmentCategory)
    {
        $this->adjustmentCategory = $adjustmentCategory;
        return $this;
    }

    /**
     * @see IChargeGroup::getReferencedCharge()
     */
    public function getReferencedCharge()
    {
        return $this->referencedCharge;
    }

    /**
     * @see IChargeGroup::setReferencedCharge()
     * @codeCoverageIgnore
     */
    public function setReferencedCharge(IReferencedCharge $referencedCharge)
    {
        $this->referencedCharge = $referencedCharge;
        return $this;
    }

    /**
     * @see IChargeGroup::getAdjustmentCharge()
     */
    public function getAdjustmentCharge()
    {
        return $this->adjustmentCharge;
    }

    /**
     * @see IChargeGroup::setAdjustmentCharge()
     * @codeCoverageIgnore
     */
    public function setAdjustmentCharge(IAdjustmentCharge $adjustmentCharge)
    {
        $this->adjustmentCharge = $adjustmentCharge;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->getReferencedCharge()->serialize()
            . $this->getAdjustmentCharge()->serialize();
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
        $adjustmentCategory = $this->getAdjustmentCategory();;
        return array_merge(
            $name ? ['name' => $name] : [],
            $adjustmentCategory ? ['adjustmentCategory' => $adjustmentCategory] : []
        );
    }
}
