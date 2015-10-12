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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use DateTime;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Order\OrderItem;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderDetailItem extends OrderItem implements IOrderDetailItem
{
    use TStatusContainer, TChargeGroupContainer;

    /** @var bool */
    protected $hasChainedLines;
    /** @var bool */
    protected $hasDerivedChild;
    /** @var string */
    protected $chainedFromOrderHeaderKey;
    /** @var string */
    protected $derivedFromOrderHeaderKey;
    /** @var float */
    protected $shippedQuantity;
    /** @var string */
    protected $carrier;
    /** @var string */
    protected $carrierMode;
    /** @var string */
    protected $carrierDisplayText;
    /** @var DateTime */
    protected $originalExpectedShipmentDateFrom;
    /** @var DateTime */
    protected $originalExpectedShipmentDateTo;
    /** @var DateTime */
    protected $originalExpectedDeliveryDateFrom;
    /** @var DateTime */
    protected $originalExpectedDeliveryDateTo;
    /** @var string */
    protected $omsLineId;
    /** @var ICustomerCareOrderItemTotals */
    protected $customerCareOrderItemTotals;

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
        $this->initOptionalExtractPaths()
            ->initDatetimeExtractPaths()
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
        $this->optionalExtractionPaths = array_merge($this->optionalExtractionPaths, [
            'chainedFromOrderHeaderKey' => '@chainedFromOrderHeaderKey',
            'derivedFromOrderHeaderKey' => '@derivedFromOrderHeaderKey',
            'hasChainedLines' => '@hasChainedLines',
            'hasDerivedChild' => '@hasDerivedChild',
            'shippedQuantity' => 'x:ShippedQuantity',
            'carrier' => 'x:Carrier',
            'carrierMode' => 'x:Carrier/@mode',
            'carrierDisplayText' => 'x:Carrier/@displayText',
            'omsLineId' => 'x:OMSLineId',
        ]);
        return $this;
    }

    /**
     * Initialize the protected class property array self::datetimeExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initDatetimeExtractPaths()
    {
        $this->datetimeExtractionPaths = array_merge($this->datetimeExtractionPaths, [
            'originalExpectedShipmentDateFrom' => 'string(x:EstimatedDeliveryDate/x:OriginalExpectedShipmentDate/x:From)',
            'originalExpectedShipmentDateTo' => 'string(x:EstimatedDeliveryDate/x:OriginalExpectedShipmentDate/x:To)',
            'originalExpectedDeliveryDateFrom' => 'string(x:EstimatedDeliveryDate/x:OriginalExpectedDeliveryDate/x:From)',
            'originalExpectedDeliveryDateTo' => 'string(x:EstimatedDeliveryDate/x:OriginalExpectedDeliveryDate/x:To)',
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
            'statuses' => 'x:Statuses',
            'customerCareOrderItemTotals' => 'x:CustomerCareOrderItemTotals',
            'chargeGroups' => 'x:ChargeGroups',
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
        $this->setStatuses($this->buildPayloadForInterface(
            self::STATUS_ITERABLE_INTERFACE
        ));
        $this->setCustomerCareOrderItemTotals($this->buildPayloadForInterface(
            self::CUSTOMER_CARE_ORDER_ITEM_TOTALS_INTERFACE
        ));
        $this->setChargeGroups($this->buildPayloadForInterface(
            self::CHARGE_GROUP_ITERABLE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IOrderDetailItem::getHasChainedLines()
     */
    public function getHasChainedLines()
    {
        return $this->hasChainedLines;
    }

    /**
     * @see IOrderDetailItem::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setHasChainedLines($hasChainedLines)
    {
        $this->hasChainedLines = $hasChainedLines;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getHasDerivedChild()
     */
    public function getHasDerivedChild()
    {
        return $this->hasDerivedChild;
    }

    /**
     * @see IOrderDetailItem::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setHasDerivedChild($hasDerivedChild)
    {
        $this->hasDerivedChild = $hasDerivedChild;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getChainedFromOrderHeaderKey()
     */
    public function getChainedFromOrderHeaderKey()
    {
        return $this->chainedFromOrderHeaderKey;
    }

    /**
     * @see IOrderDetailItem::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setChainedFromOrderHeaderKey($chainedFromOrderHeaderKey)
    {
        $this->chainedFromOrderHeaderKey = $chainedFromOrderHeaderKey;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getDerivedFromOrderHeaderKey()
     */
    public function getDerivedFromOrderHeaderKey()
    {
        return $this->derivedFromOrderHeaderKey;
    }

    /**
     * @see IOrderDetailItem::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setDerivedFromOrderHeaderKey($derivedFromOrderHeaderKey)
    {
        $this->derivedFromOrderHeaderKey = $derivedFromOrderHeaderKey;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getShippedQuantity()
     */
    public function getShippedQuantity()
    {
        return $this->shippedQuantity;
    }

    /**
     * @see IOrderDetailItem::setShippedQuantity()
     * @codeCoverageIgnore
     */
    public function setShippedQuantity($shippedQuantity)
    {
        $this->shippedQuantity = $shippedQuantity;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getCarrier()
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @see IOrderDetailItem::setCarrier()
     * @codeCoverageIgnore
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getCarrierMode()
     */
    public function getCarrierMode()
    {
        return $this->carrierMode;
    }

    /**
     * @see IOrderDetailItem::setCarrierMode()
     * @codeCoverageIgnore
     */
    public function setCarrierMode($carrierMode)
    {
        $this->carrierMode = $carrierMode;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getCarrierDisplayText()
     */
    public function getCarrierDisplayText()
    {
        return $this->carrierDisplayText;
    }

    /**
     * @see IOrderDetailItem::setCarrierDisplayText()
     * @codeCoverageIgnore
     */
    public function setCarrierDisplayText($carrierDisplayText)
    {
        $this->carrierDisplayText = $carrierDisplayText;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getOriginalExpectedShipmentDateFrom()
     */
    public function getOriginalExpectedShipmentDateFrom()
    {
        return $this->originalExpectedShipmentDateFrom;
    }

    /**
     * @see IOrderDetailItem::setOriginalExpectedShipmentDateFrom()
     * @codeCoverageIgnore
     */
    public function setOriginalExpectedShipmentDateFrom(DateTime $originalExpectedShipmentDateFrom)
    {
        $this->originalExpectedShipmentDateFrom = $originalExpectedShipmentDateFrom;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getOriginalExpectedShipmentDateTo()
     */
    public function getOriginalExpectedShipmentDateTo()
    {
        return $this->originalExpectedShipmentDateTo;
    }

    /**
     * @see IOrderDetailItem::setOriginalExpectedShipmentDateTo()
     * @codeCoverageIgnore
     */
    public function setOriginalExpectedShipmentDateTo(DateTime $originalExpectedShipmentDateTo)
    {
        $this->originalExpectedShipmentDateTo = $originalExpectedShipmentDateTo;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getOriginalExpectedDeliveryDateFrom()
     */
    public function getOriginalExpectedDeliveryDateFrom()
    {
        return $this->originalExpectedDeliveryDateFrom;
    }

    /**
     * @see IOrderDetailItem::setOriginalExpectedDeliveryDateFrom()
     * @codeCoverageIgnore
     */
    public function setOriginalExpectedDeliveryDateFrom(DateTime $originalExpectedDeliveryDateFrom)
    {
        $this->originalExpectedDeliveryDateFrom = $originalExpectedDeliveryDateFrom;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getOriginalExpectedDeliveryDateTo()
     */
    public function getOriginalExpectedDeliveryDateTo()
    {
        return $this->originalExpectedDeliveryDateTo;
    }

    /**
     * @see IOrderDetailItem::setOriginalExpectedDeliveryDateTo()
     * @codeCoverageIgnore
     */
    public function setOriginalExpectedDeliveryDateTo(DateTime $originalExpectedDeliveryDateTo)
    {
        $this->originalExpectedDeliveryDateTo = $originalExpectedDeliveryDateTo;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getOmsLineId()
     */
    public function getOmsLineId()
    {
        return $this->omsLineId;
    }

    /**
     * @see IOrderDetailItem::setOmsLineId()
     * @codeCoverageIgnore
     */
    public function setOmsLineId($omsLineId)
    {
        $this->omsLineId = $omsLineId;
        return $this;
    }

    /**
     * @see IOrderDetailItem::getCustomerCareOrderItemTotals()
     */
    public function getCustomerCareOrderItemTotals()
    {
        return $this->customerCareOrderItemTotals;
    }

    /**
     * @see IOrderDetailItem::setCustomerCareOrderItemTotals()
     * @codeCoverageIgnore
     */
    public function setCustomerCareOrderItemTotals(ICustomerCareOrderItemTotals $customerCareOrderItemTotals)
    {
        $this->customerCareOrderItemTotals = $customerCareOrderItemTotals;
        return $this;
    }

    protected function getRootAttributes()
    {
        $hasChainedLines = $this->getHasChainedLines();
        $hasDerivedChild = $this->getHasDerivedChild();
        $chainedFromOrderHeaderKey = $this->getChainedFromOrderHeaderKey();
        $derivedFromOrderHeaderKey = $this->getDerivedFromOrderHeaderKey();
        return array_merge(
            parent::getRootAttributes(),
            !empty($hasChainedLines) ? ['hasChainedLines' => $hasChainedLines] : [],
            !empty($hasDerivedChild) ? ['hasDerivedChild' => $hasDerivedChild] : [],
            $chainedFromOrderHeaderKey ? ['chainedFromOrderHeaderKey' => $chainedFromOrderHeaderKey] : [],
            $derivedFromOrderHeaderKey ? ['derivedFromOrderHeaderKey' => $derivedFromOrderHeaderKey] : []
        );
    }

    protected function serializeContents()
    {
        return $this->serializeRequiredValue('ItemId', $this->xmlEncode($this->getItemId()))
            . $this->serializeRequiredValue('Quantity', $this->xmlEncode($this->getQuantity()))
            . $this->serializeOptionalXmlEncodedValue('ShippedQuantity', $this->getShippedQuantity())
            . $this->serializeDescription()
            . $this->serializeOptionalXmlEncodedValue('Department', $this->getDepartment())
            . $this->serializePricing()
            . $this->serializeShippingProgram()
            . $this->serializeCarrierValue('Carrier', $this->getCarrier())
            . $this->serializeShippingMethod()
            . $this->serializeOptionalSubpayload($this->getStoreFrontDetails())
            . $this->serializeOptionalXmlEncodedValue('FulfillmentChannel', $this->getFulfillmentChannel())
            . $this->serializeOptionalSubpayload($this->getProxyPickupDetails())
            . $this->serializeEstimatedDeliveryDate()
            . $this->serializeNamedDeliveryDate()
            . $this->serializeOptionalXmlEncodedValue('DeliveryInstructions', $this->getDeliveryInstructions())
            . $this->serializeOptionalXmlEncodedValue('VendorId', $this->getVendorId())
            . $this->serializeOptionalXmlEncodedValue('VendorName', $this->getVendorName())
            . $this->serializeGifting()
            . $this->serializeOptionalXmlEncodedValue('ShopRunnerMessage', $this->getShopRunnerMessage())
            . $this->serializeCustomizations()
            . $this->getStatuses()->serialize()
            . $this->serializeOptionalXmlEncodedValue('OMSLineId', $this->getOmsLineId())
            . $this->getCustomerCareOrderItemTotals()->serialize()
            . $this->serializeOptionalXmlEncodedValue('SerialNumber', $this->getSerialNumber())
            . $this->getCustomAttributes()->serialize()
            . $this->serializeOptionalXmlEncodedValue('GiftRegistryCancelUrl', $this->getGiftRegistryCancelUrl())
            . $this->serializeOptionalXmlEncodedValue('ReservationId', $this->getReservationId())
            . $this->getChargeGroups()->serialize();
    }

    /**
     * Create an XML serialization of the estimated deliver date data. Will
     * only include any data when there is useful EDD data to include.
     *
     * @return string
     */
    protected function serializeEstimatedDeliveryDate()
    {
        if ($this->hasAnyEstimatedDeliveryDateData()) {
            return '<EstimatedDeliveryDate>'
                . $this->serializeEstimatedWindow(
                    'DeliveryWindow',
                    $this->getEstimatedDeliveryWindowFrom(),
                    $this->getEstimatedDeliveryWindowTo()
                )
                . $this->serializeEstimatedWindow(
                    'ShippingWindow',
                    $this->getEstimatedShippingWindowFrom(),
                    $this->getEstimatedShippingWindowTo()
                )
                . $this->serializeOptionalXmlEncodedValue('Mode', $this->getEstimatedDeliveryMode())
                . $this->serializeRequiredValue('MessageType', $this->xmlEncode($this->getEstimatedDeliveryMessageType()))
                . $this->serializeOptionalXmlEncodedValue('Template', $this->getEstimatedDeliveryTemplate())
                . $this->serializeEstimatedWindow(
                    'OriginalExpectedShipmentDate',
                    $this->getOriginalExpectedShipmentDateFrom(),
                    $this->getOriginalExpectedShipmentDateTo()
                )
                . $this->serializeEstimatedWindow(
                    'OriginalExpectedDeliveryDate',
                    $this->getOriginalExpectedDeliveryDateFrom(),
                    $this->getOriginalExpectedDeliveryDateTo()
                )
                . '</EstimatedDeliveryDate>';
        }
        return '';
    }

    /**
     * Serialize the carrier XML node.
     *
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeCarrierValue($nodeName, $value)
    {
        $modeAttribute = $this->serializeOptionalAttribute('mode', $this->xmlEncode($this->getCarrierMode()));
        $displayTextAttribute = $this->serializeOptionalAttribute('displayText', $this->xmlEncode($this->getCarrierDisplayText()));
        return $value
            ? sprintf('<%s %s %s>%s</%1$s>', $nodeName, $modeAttribute, $displayTextAttribute, $this->xmlEncode($value)) : null;
    }
}
