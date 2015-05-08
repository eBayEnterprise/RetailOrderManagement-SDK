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
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class TaxedPriceGroup implements ITaxedPriceGroup
{
    use TPayload, TAmount, TPriceGroup, TTaxContainer, TTaxedDiscountContainer;

    const ROOT_NODE = 'Pricing';

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
            'amount' => 'number(x:Amount)',
        ];
        $this->optionalExtractionPaths = [
            'taxClass' => 'x:TaxData/x:TaxClass',
        ];
        $this->subpayloadExtractionPaths = [
            'discounts' => 'x:PromotionalDiscounts',
            'taxes' => 'x:TaxData/x:Taxes',
        ];

        $this->taxes = $this->buildPayloadForInterface(
            self::TAX_ITERABLE_INTERFACE
        );
        $this->discounts = $this->buildPayloadForInterface(
            self::DISCOUNT_ITERABLE_INTERFACE
        );
    }

    protected function serializeContents()
    {
        return $this->serializePriceGroupAmount()
            . $this->serializeTaxData()
            . $this->getDiscounts()->serialize();
    }

    /**
     * perform additional sanitization
     * @return self
     */
    protected function deserializeExtra()
    {
        $this->setAmount($this->amount);
        return $this;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getRootNodeName()
    {
        return !is_null($this->rootNodeName) ? $this->rootNodeName : static::ROOT_NODE;
    }
}
