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
use eBayEnterprise\RetailOrderManagement\Payload\Order\ITax;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TaxCharge extends Charge implements ITaxCharge
{
    const TAX_ROOT_NODE = 'TaxCharge';

    /** @var string */
    protected $taxType;
    /** @var ITax */
    protected $tax;

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
        parent::__construct($validators, $schemaValidator, $payloadMap, $logger, $parentPayload);
        $this->initSubPayloadExtractPaths()
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
        parent::initOptionalExtractPaths();
        $this->optionalExtractionPaths = array_merge($this->optionalExtractionPaths, [
            'taxType' => '@taxType',
        ]);
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
        $this->subpayloadExtractionPaths = array_merge($this->subpayloadExtractionPaths, [
            'tax' => 'x:Tax',
        ]);
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setTax($this->buildPayloadForInterface(
            static::TAX_INTERFACE
        ));
        return $this;
    }

    /**
     * @see ITaxCharge::getTaxType()
     */
    public function getTaxType()
    {
        return $this->taxType;
    }

    /**
     * @see ITaxCharge::setTaxType()
     * @codeCoverageIgnore
     */
    public function setTaxType($taxType)
    {
        $this->taxType = $taxType;
        return $this;
    }

    /**
     * @see ITaxCharge::getTax()
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @see ITaxCharge::setTax()
     * @codeCoverageIgnore
     */
    public function setTax(ITax $tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->getTax()->serialize();
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::TAX_ROOT_NODE;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $taxType = $this->getTaxType();
        return array_merge(
            parent::getRootAttributes(),
            $taxType ? ['taxType' => $taxType] : []
        );
    }
}
