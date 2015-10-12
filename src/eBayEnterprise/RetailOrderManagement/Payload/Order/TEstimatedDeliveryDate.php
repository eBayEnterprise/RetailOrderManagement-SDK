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

use DateTime;
use DOMXPath;

trait TEstimatedDeliveryDate
{
    /** @var DateTime */
    protected $estimatedDeliveryWindowFrom;
    /** @var DateTime */
    protected $estimatedDeliveryWindowTo;
    /** @var DateTime */
    protected $estimatedShippingWindowFrom;
    /** @var DateTime */
    protected $estimatedShippingWindowTo;
    /** @var string */
    protected $estimatedDeliveryMode;
    /** @var string */
    protected $estimatedDeliveryMessageType;
    /** @var string */
    protected $estimatedDeliveryTemplate;
    /** @var array */
    protected $allowedDeliveryModes = [
        self::MODE_ENABLED,
        self::MODE_CALIBRATION,
        self::MODE_LEGACY,
    ];
    /** @var array */
    protected $allowedMessageTypes = [
        self::MESSAGE_TYPE_DELIVERYDATE,
        self::MESSAGE_TYPE_SHIPDATE,
        self::MESSAGE_TYPE_NONE,
    ];

    public function getEstimatedDeliveryWindowFrom()
    {
        return $this->estimatedDeliveryWindowFrom;
    }

    public function setEstimatedDeliveryWindowFrom(DateTime $estimatedDeliveryWindowFrom)
    {
        $this->estimatedDeliveryWindowFrom = $estimatedDeliveryWindowFrom;
        return $this;
    }

    public function getEstimatedDeliveryWindowTo()
    {
        return $this->estimatedDeliveryWindowTo;
    }

    public function setEstimatedDeliveryWindowTo(DateTime $estimatedDeliveryWindowTo)
    {
        $this->estimatedDeliveryWindowTo = $estimatedDeliveryWindowTo;
        return $this;
    }

    public function getEstimatedShippingWindowFrom()
    {
        return $this->estimatedShippingWindowFrom;
    }

    public function setEstimatedShippingWindowFrom(DateTime $estimatedShippingWindowFrom)
    {
        $this->estimatedShippingWindowFrom = $estimatedShippingWindowFrom;
        return $this;
    }

    public function getEstimatedShippingWindowTo()
    {
        return $this->estimatedShippingWindowTo;
    }

    public function setEstimatedShippingWindowTo(DateTime $estimatedShippingWindowTo)
    {
        $this->estimatedShippingWindowTo = $estimatedShippingWindowTo;
        return $this;
    }

    public function getEstimatedDeliveryMode()
    {
        return $this->estimatedDeliveryMode;
    }

    public function setEstimatedDeliveryMode($estimatedDeliveryMode)
    {
        $this->estimatedDeliveryMode = in_array($estimatedDeliveryMode, $this->allowedDeliveryModes)
            ? $estimatedDeliveryMode
            : null;
        return $this;
    }

    public function getEstimatedDeliveryMessageType()
    {
        return $this->estimatedDeliveryMessageType;
    }

    public function setEstimatedDeliveryMessageType($estimatedDeliveryMessageType)
    {
        $this->estimatedDeliveryMessageType = in_array($estimatedDeliveryMessageType, $this->allowedMessageTypes)
            ? $estimatedDeliveryMessageType
            : null;
        return $this;
    }

    public function getEstimatedDeliveryTemplate()
    {
        return $this->estimatedDeliveryTemplate;
    }

    public function setEstimatedDeliveryTemplate($estimatedDeliveryTemplate)
    {
        $this->estimatedDeliveryTemplate = $estimatedDeliveryTemplate;
        return $this;
    }

    /**
     * Check for the payload to contain any useful estimated delivery date
     * data.
     *
     * @return bool
     */
    protected function hasAnyEstimatedDeliveryDateData()
    {
        return ($this->getEstimatedDeliveryWindowFrom() && $this->getEstimatedDeliveryWindowTo())
            || ($this->getEstimatedShippingWindowFrom() && $this->getEstimatedShippingWindowTo())
            || $this->getEstimatedDeliveryMode()
            || $this->getEstimatedDeliveryTemplate();
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
                . "<MessageType>{$this->xmlEncode($this->getEstimatedDeliveryMessageType())}</MessageType>"
                . $this->serializeOptionalXmlEncodedValue('Template', $this->getEstimatedDeliveryTemplate())
                . '</EstimatedDeliveryDate>';
        }
        return '';
    }

    /**
     * Serialize valid date windows into an XML node with From and To nodes
     * for the date window. If either date is empty, will return an empty string.
     *
     * @param string
     * @param DateTime
     * @param DateTime
     * @return string
     */
    protected function serializeEstimatedWindow($nodeName, DateTime $fromDate = null, DateTime $toDate = null)
    {
        return ($fromDate && $toDate)
            ? "<$nodeName><From>{$fromDate->format('c')}</From><To>{$toDate->format('c')}</To></$nodeName>"
            : '';
    }

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
