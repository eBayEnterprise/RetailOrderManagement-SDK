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

class PriceGroup implements IPriceGroup
{
    use TPayload, TAmount, TDiscountContainer, TTaxContainer;

    const ROOT_NODE = 'Pricing';

    /** @var float */
    protected $amount;
    /** @var float */
    protected $remainder;
    /** @var float */
    protected $unitPrice;
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
            'remainder' => 'x:Amount/@remainder',
            'unitPrice' => 'x:UnitPrice',
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

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    public function getRemainder()
    {
        return $this->remainder;
    }

    public function setRemainder($remainder)
    {
        $this->remainder = $this->sanitizeAmount($remainder);
        return $this;
    }

    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $this->sanitizeAmount($unitPrice);
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

    protected function serializeContents()
    {
        $unitPrice = $this->getUnitPrice();
        return $this->serializePriceGroupAmount()
            . $this->getDiscounts()->serialize()
            . $this->serializeTaxData()
            . (is_numeric($unitPrice) ? $this->serializeAmount('UnitPrice', $this->getUnitPrice()) : '');
    }

    /**
     * Serialize the price group amount value, including a remainder attribute
     * if a remainder amount has been set.
     *
     * @return string | null
     */
    protected function serializePriceGroupAmount()
    {
        $rawRemainder = $this->getRemainder();
        $remainder = $rawRemainder ? $this->formatAmount($rawRemainder) : null;
        $amount = $this->getAmount();
        return is_numeric($amount)
            ? "<Amount {$this->serializeOptionalAttribute('remainder', $remainder)}>"
            . $this->formatAmount($amount)
            . '</Amount>'
            : null;
    }

    /**
     * perform additional sanitization
     * @return self
     */
    protected function deserializeExtra()
    {
        $this->amount = $this->sanitizeAmount($this->amount);
        $this->remainder = $this->sanitizeAmount($this->remainder);
        $this->unitPrice = $this->sanitizeAmount($this->unitPrice);
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
