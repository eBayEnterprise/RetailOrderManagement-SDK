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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TaxedDutyPriceGroup extends TaxedPriceGroup implements ITaxedDutyPriceGroup
{
    const ROOT_NODE = 'Pricing';

    /** @var string */
    protected $calculationError;
    /** @var string */
    protected $rootNodeName;

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

        $this->optionalExtractionPaths = array_merge(
            $this->optionalExtractionPaths,
            ['calculationError' => 'x:CalculationError']
        );
    }

    public function getCalculationError()
    {
        return $this->calculationError;
    }

    public function setCalculationError($message)
    {
        $this->calculationError = $message;
        return $this;
    }

    protected function serializeContents()
    {
        $calculationError = $this->getCalculationError();
        return $this->serializePriceGroupAmount()
            . $this->serializeTaxData()
            . $this->getDiscounts()->serialize()
            . ($calculationError ? $calculationError : '');
    }

    /**
     * perform additional sanitization
     * @return self
     */
    protected function deserializeExtra()
    {
        $this->amount = $this->sanitizeAmount($this->amount);
        return $this;
    }

    /**
     * Dynamically set the name of the root node the price group gets serialized
     * with. As this type can represent a variant of pricing information,
     * serializations will vary based upon context.
     *
     * @param string Must be a valid XML node name
     */
    public function setRootNodeName($nodeName)
    {
        $this->rootNodeName = $nodeName;
        return $this;
    }

    /**
     * If a root node name has been injected, use that as the root node name
     * for the serialization, otherwise, fall back to the static const.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return !is_null($this->rootNodeName) ? $this->rootNodeName : static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
