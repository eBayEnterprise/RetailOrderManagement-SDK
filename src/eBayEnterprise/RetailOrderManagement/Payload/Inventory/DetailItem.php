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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;
use Psr\Log\LoggerInterface;
use \DateTime;

/**
 * Inventory delivery details for an item which can be fulfilled.
 */
class DetailItem implements IDetailItem
{
    use TPayload, TItem, TPhysicalAddress {
        TPhysicalAddress::getLines as getAddressLines;
        TPhysicalAddress::setLines as setAddressLines;
        TPhysicalAddress::getCity as getAddressCity;
        TPhysicalAddress::setCity as setAddressCity;
        TPhysicalAddress::getMainDivision as getAddressMainDivision;
        TPhysicalAddress::setMainDivision as setAddressMainDivision;
        TPhysicalAddress::getCountryCode as getAddressCountryCode;
        TPhysicalAddress::setCountryCode as setAddressCountryCode;
        TPhysicalAddress::getPostalCode as getAddressPostalCode;
        TPhysicalAddress::setPostalCode as setAddressPostalCode;
    }

    const ROOT_NODE = 'InventoryDetail';
    const SHIP_FROM_ADDRESS = 'ShipFromAddress';

    /** @var DateTime */
    protected $deliveryWindowFromDate;
    /** @var DateTime */
    protected $deliveryWindowToDate;
    /** @var DateTime */
    protected $shippingWindowFromDate;
    /** @var DateTime */
    protected $shippingWindowToDate;
    /** @var DateTime */
    protected $deliveryEstimateCreationTime;
    /** @var DateTime */
    protected $deliveryEstimateDisplayFlag;
    /** @var string */
    protected $deliveryEstimateMessage;

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
            'itemId' => 'string(@itemId)',
            'id' => 'string(@lineId)',
            'deliveryWindowFromDate' => 'string(x:DeliveryEstimate/x:DeliveryWindow/x:From)',
            'deliveryWindowToDate' => 'string(x:DeliveryEstimate/x:DeliveryWindow/x:To)',
            'shippingWindowFromDate' => 'string(x:DeliveryEstimate/x:ShippingWindow/x:From)',
            'shippingWindowToDate' => 'string(x:DeliveryEstimate/x:ShippingWindow/x:To)',
            'deliveryEstimateCreationTime' => 'string(x:DeliveryEstimate/x:CreationTime)',
            'deliveryEstimateDisplayFlag' => 'string(x:DeliveryEstimate/x:Display)',
            'city' => 'string(x:ShipFromAddress/x:City)',
            'countryCode' => 'string(x:ShipFromAddress/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'deliveryEstimateMessage' => 'x:DeliveryEstimate/x:Message',
            'mainDivision' => 'x:ShipFromAddress/x:MainDivision',
            'postalCode' => 'x:ShipFromAddress/x:PostalCode',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'lines',
                'xPath' => 'x:ShipFromAddress/*[starts-with(name(), "Line")]'
            ],
        ];
    }

    /**
     * The earliest date when the order line item is expected to arrive at the ship-to address.
     *
     * @return DateTime
     */
    public function getDeliveryWindowFromDate()
    {
        return $this->deliveryWindowFromDate;
    }

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryWindowFromDate(DateTime $deliveryWindowFromDate)
    {
        $this->deliveryWindowFromDate = $deliveryWindowFromDate;
        return $this;
    }

    /**
     * The latest date when the order line item is expected to arrive at the ship-to address.
     *
     * @return DateTime
     */
    public function getDeliveryWindowToDate()
    {
        return $this->deliveryWindowToDate;
    }

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryWindowToDate(DateTime $deliveryWindowToDate)
    {
        $this->deliveryWindowToDate = $deliveryWindowToDate;
        return $this;
    }

    /**
     * The earliest date when the order line item is expected to leave the fulfillment node.
     *
     * @return DateTime
     */
    public function getShippingWindowFromDate()
    {
        return $this->shippingWindowFromDate;
    }

    /**
     * @param DateTime
     * @return self
     */
    public function setShippingWindowFromDate(DateTime $shippingWindowFromDate)
    {
        $this->shippingWindowFromDate = $shippingWindowFromDate;
        return $this;
    }

    /**
     * The latest date when the order line item is expected to leave the fulfillment node.
     *
     * @return DateTime
     */
    public function getShippingWindowToDate()
    {
        return $this->shippingWindowToDate;
    }

    /**
     * @param DateTime
     * @return self
     */
    public function setShippingWindowToDate(DateTime $shippingWindowToDate)
    {
        $this->shippingWindowToDate = $shippingWindowToDate;
        return $this;
    }

    /**
     * The date-time when this delivery estimate was created
     *
     * @return DateTime
     */
    public function getDeliveryEstimateCreationTime()
    {
        return $this->deliveryEstimateCreationTime;
    }

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryEstimateCreationTime(DateTime $deliveryEstimateCreationTime)
    {
        $this->deliveryEstimateCreationTime = $deliveryEstimateCreationTime;
        return $this;
    }

    /**
     * Indicates if the delivery estimate should be displayed.
     *
     * @return DateTime
     */
    public function getDeliveryEstimateDisplayFlag()
    {
        return $this->deliveryEstimateDisplayFlag;
    }

    /**
     * @param DateTime
     * @return self
     */
    public function setDeliveryEstimateDisplayFlag(DateTime $deliveryEstimateDisplayFlag)
    {
        $this->deliveryEstimateDisplayFlag = $deliveryEstimateDisplayFlag;
        return $this;
    }

    /**
     * not currently used.
     *
     * restrictions: optional
     * @return string
     */
    public function getDeliveryEstimateMessage()
    {
        return $this->deliveryEstimateMessage;
    }

    /**
     * @param string
     * @return self
     */
    public function setDeliveryEstimateMessage($deliveryEstimateMessage)
    {
        $this->deliveryEstimateMessage = $deliveryEstimateMessage;
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializeDeliveryEstimate()
            . $this->serializePhysicalAddress();
    }

    protected function serializeDeliveryEstimate()
    {
        return '<DeliveryEstimate>'
            . '<DeliveryWindow>'
            . $this->serializeDateTime('From', $this->getDeliveryWindowFromDate())
            . $this->serializeDateTime('To', $this->getDeliveryWindowToDate())
            . '</DeliveryWindow>'
            . '<ShippingWindow>'
            . $this->serializeDateTime('From', $this->getShippingWindowFromDate())
            . $this->serializeDateTime('To', $this->getShippingWindowToDate())
            . '</ShippingWindow>'
            . $this->serializeDateTime('CreationTime', $this->getDeliveryEstimateCreationTime())
            . "<Display>{$this->convertBooleanToString($this->getDeliveryEstimateDisplayFlag())}</Display>"
            . $this->serializeOptionalValue('Message', $this->getDeliveryEstimateMessage())
            . '</DeliveryEstimate>';
    }

    protected function serializeDateTime($nodeName, $dateTime)
    {
        return is_null($dateTime) ? "<{$nodeName}/>" :
            "<{$nodeName}>{$dateTime->format('c')}</{$nodeName}>";
    }

    protected function deserializeExtra()
    {
        $dateFields = [
            'deliveryWindowFromDate',
            'deliveryWindowToDate',
            'shippingWindowFromDate',
            'shippingWindowToDate',
            'deliveryEstimateCreationTime',
        ];
        foreach ($dateFields as $property) {
            $dateTime = $this->$property ? new DateTime($this->$property) : null;
            // ensure the property is set to null if the datetime creation fails
            $this->$property = $dateTime ?: null;
        }
        $this->deliveryEstimateDisplayFlag = $this->convertStringToBoolean($this->deliveryEstimateDisplayFlag);
    }

    protected function getPhysicalAddressRootNodeName()
    {
        return static::SHIP_FROM_ADDRESS;
    }

    protected function getRootAttributes()
    {
        return [
            'itemId' => $this->getItemId(),
            'lineId' => $this->getId(),
        ];
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
