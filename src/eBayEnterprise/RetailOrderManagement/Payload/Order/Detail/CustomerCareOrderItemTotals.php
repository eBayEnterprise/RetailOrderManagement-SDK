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

class CustomerCareOrderItemTotals implements ICustomerCareOrderItemTotals
{
    use TPayload;

    /** @var float */
    protected $charges;
    /** @var float */
    protected $discount;
    /** @var float */
    protected $extendedPrice;
    /** @var float */
    protected $lineTotal;
    /** @var float */
    protected $lineTotalWithoutTax;
    /** @var float */
    protected $pricingQty;
    /** @var float */
    protected $shippingCharges;
    /** @var float */
    protected $shippingDiscount;
    /** @var float */
    protected $shippingTotal;
    /** @var float */
    protected $tax;
    /** @var float */
    protected $unitPrice;
    /** @var string */
    protected $minLineStatus;
    /** @var string */
    protected $minLineStatusDescription;
    /** @var string */
    protected $maxLineStatus;
    /** @var string */
    protected $maxLineStatusDescription;

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
        $this->isSerializeEmptyNode = false;
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
            'charges' => 'x:Charges',
            'discount' => 'x:Discount',
            'extendedPrice' => 'x:ExtendedPrice',
            'lineTotal' => 'x:LineTotal',
            'lineTotalWithoutTax' => 'x:LineTotalWithoutTax',
            'pricingQty' => 'x:PricingQty',
            'shippingCharges' => 'x:ShippingCharges',
            'shippingDiscount' => 'x:ShippingDiscount',
            'shippingTotal' => 'x:ShippingTotal',
            'tax' => 'x:Tax',
            'unitPrice' => 'x:UnitPrice',
            'minLineStatus' => 'x:MinLineStatus',
            'minLineStatusDescription' => 'x:MinLineStatus/@description',
            'maxLineStatus' => 'x:MaxLineStatus',
            'maxLineStatusDescription' => 'x:MaxLineStatus/@description',
        ];
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getCharges()
     */
    public function getCharges()
    {
        return $this->charges;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setCharges()
     * @codeCoverageIgnore
     */
    public function setCharges($charges)
    {
        $this->charges = $charges;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getDiscount()
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setDiscount()
     * @codeCoverageIgnore
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getExtendedPrice()
     */
    public function getExtendedPrice()
    {
        return $this->extendedPrice;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setExtendedPrice()
     * @codeCoverageIgnore
     */
    public function setExtendedPrice($extendedPrice)
    {
        $this->extendedPrice = $extendedPrice;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getLineTotal()
     */
    public function getLineTotal()
    {
        return $this->lineTotal;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setLineTotal()
     * @codeCoverageIgnore
     */
    public function setLineTotal($lineTotal)
    {
        $this->lineTotal = $lineTotal;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getLineTotalWithoutTax()
     */
    public function getLineTotalWithoutTax()
    {
        return $this->lineTotalWithoutTax;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setLineTotalWithoutTax()
     * @codeCoverageIgnore
     */
    public function setLineTotalWithoutTax($lineTotalWithoutTax)
    {
        $this->lineTotalWithoutTax = $lineTotalWithoutTax;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getPricingQty()
     */
    public function getPricingQty()
    {
        return $this->pricingQty;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setPricingQty()
     * @codeCoverageIgnore
     */
    public function setPricingQty($pricingQty)
    {
        $this->pricingQty = $pricingQty;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getShippingCharges()
     */
    public function getShippingCharges()
    {
        return $this->shippingCharges;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setShippingCharges()
     * @codeCoverageIgnore
     */
    public function setShippingCharges($shippingCharges)
    {
        $this->shippingCharges = $shippingCharges;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getShippingDiscount()
     */
    public function getShippingDiscount()
    {
        return $this->shippingDiscount;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setShippingDiscount()
     * @codeCoverageIgnore
     */
    public function setShippingDiscount($shippingDiscount)
    {
        $this->shippingDiscount = $shippingDiscount;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getShippingTotal()
     */
    public function getShippingTotal()
    {
        return $this->shippingTotal;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setShippingTotal()
     * @codeCoverageIgnore
     */
    public function setShippingTotal($shippingTotal)
    {
        $this->shippingTotal = $shippingTotal;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getTax()
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setTax()
     * @codeCoverageIgnore
     */
    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getUnitPrice()
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setUnitPrice()
     * @codeCoverageIgnore
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getMinLineStatus()
     */
    public function getMinLineStatus()
    {
        return $this->minLineStatus;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setMinLineStatus()
     * @codeCoverageIgnore
     */
    public function setMinLineStatus($minLineStatus)
    {
        $this->minLineStatus = $minLineStatus;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getMinLineStatusDescription()
     */
    public function getMinLineStatusDescription()
    {
        return $this->minLineStatusDescription;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setMinLineStatusDescription()
     * @codeCoverageIgnore
     */
    public function setMinLineStatusDescription($minLineStatusDescription)
    {
        $this->minLineStatusDescription = $minLineStatusDescription;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getMaxLineStatus()
     */
    public function getMaxLineStatus()
    {
        return $this->maxLineStatus;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setMaxLineStatus()
     * @codeCoverageIgnore
     */
    public function setMaxLineStatus($maxLineStatus)
    {
        $this->maxLineStatus = $maxLineStatus;
        return $this;
    }

    /**
     * @see ICustomerCareOrderItemTotals::getMaxLineStatusDescription()
     */
    public function getMaxLineStatusDescription()
    {
        return $this->maxLineStatusDescription;
    }

    /**
     * @see ICustomerCareOrderItemTotals::setMaxLineStatusDescription()
     * @codeCoverageIgnore
     */
    public function setMaxLineStatusDescription($maxLineStatusDescription)
    {
        $this->maxLineStatusDescription = $maxLineStatusDescription;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeOptionalNumericValue('Charges', $this->getCharges())
            . $this->serializeOptionalNumericValue('Discount', $this->getDiscount())
            . $this->serializeOptionalNumericValue('ExtendedPrice', $this->getExtendedPrice())
            . $this->serializeOptionalNumericValue('LineTotal', $this->getLineTotal())
            . $this->serializeOptionalNumericValue('LineTotalWithoutTax', $this->getLineTotalWithoutTax())
            . $this->serializeOptionalNumericValue('PricingQty', $this->getLineTotalWithoutTax())
            . $this->serializeOptionalNumericValue('ShippingCharges', $this->getShippingCharges())
            . $this->serializeOptionalNumericValue('ShippingDiscount', $this->getShippingDiscount())
            . $this->serializeOptionalNumericValue('ShippingTotal', $this->getShippingTotal())
            . $this->serializeOptionalNumericValue('Tax', $this->getTax())
            . $this->serializeOptionalNumericValue('UnitPrice', $this->getUnitPrice())
            . $this->serializeLineStatusValue('MinLineStatus', $this->getMinLineStatus(), $this->getMinLineStatusDescription())
            . $this->serializeLineStatusValue('MaxLineStatus', $this->getMaxLineStatus(), $this->getMaxLineStatusDescription());
    }

    /**
     * Serialize optional node of type Numeric values, which include
     * string with digits, int, and float data types.
     *
     * @param string
     * @param mixed
     * @return string | null
     */
    protected function serializeOptionalNumericValue($nodeName, $value)
    {
        return is_numeric($value) ? $this->serializeRequiredValue($nodeName, $value) : null;
    }

    /**
     * Serialize Min/Max line status XML node.
     *
     * @param  string
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeLineStatusValue($nodeName, $value, $description)
    {
        $descriptionAttribute = $this->serializeOptionalAttribute('description', $description);
        return $value
            ? sprintf('<%s %s>%s</%1$s>', $nodeName, $descriptionAttribute, $this->xmlEncode($value)) : null;
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
}
