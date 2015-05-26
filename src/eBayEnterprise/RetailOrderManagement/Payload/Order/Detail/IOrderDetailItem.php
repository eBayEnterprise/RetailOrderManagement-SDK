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
use eBayEnterprise\RetailOrderManagement\Payload\Order\IOrderItem;
use DateTime;

interface IOrderDetailItem extends IStatusContainer, IChargeGroupContainer, IOrderItem
{
    const CUSTOMER_CARE_ORDER_ITEM_TOTALS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\ICustomerCareOrderItemTotals';

    /**
     * Indicates if this order item has chained or related items. This attribute is
     * expected only in response as chained orders get spawned off in OMS. Typical
     * use case is when an order with drop-ship items gets released in OMS, it would
     * then spawn off chained orders that are related to the original order.
     *
     * @return bool
     */
    public function getHasChainedLines();

    /**
     * @param  bool
     * @return self
     */
    public function setHasChainedLines($hasChainedLines);

    /**
     * Indicates if this order item has orders derived from it. This attribute is expected
     * only in response as derived orders get created in OMS. Typical use case is when an
     * item is returned in an order. This would create a return order that is related to
     * the original order.
     *
     * @return bool
     */
    public function getHasDerivedChild();

    /**
     * @param  bool
     * @return self
     */
    public function setHasDerivedChild($hasDerivedChild);

    /**
     * This attribute is expected only in response. Contains the order header that is associated
     * with this chained order item. If this item is chained from another order item, this key will
     * point to the parent order item's header record. Typically, this is used to query for
     * chained parent orders using related order search.
     *
     * @return string
     */
    public function getChainedFromOrderHeaderKey();

    /**
     * @param string
     * @return self
     */
    public function setChainedFromOrderHeaderKey($chainedFromOrderHeaderKey);

    /**
     * This attribute is expected only in response. The order header that is associated with this
     * derived order item. If this item was derived from another order item, this key will point
     * to the parent order item's header record. Typically, this is used to query for parent
     * orders using related order search.
     *
     * @return string
     */
    public function getDerivedFromOrderHeaderKey();

    /**
     * @param string
     * @return self
     */
    public function setDerivedFromOrderHeaderKey($derivedFromOrderHeaderKey);

    /**
     * Only populated for order history details
     *
     * @return float
     */
    public function getShippedQuantity();

    /**
     * @param  float
     * @return self
     */
    public function setShippedQuantity($shippedQuantity);

    /**
     * Populated on response from OMS. Represents the logical Carriers (SCAC) and modes (CarrierServiceCode)
     *
     * @return string
     */
    public function getCarrier();

    /**
     * @param string
     * @return self
     */
    public function setCarrier($carrier);

    /**
     * @return string
     */
    public function getCarrierMode();

    /**
     * @param string
     * @return self
     */
    public function setCarrierMode($carrierMode);

    /**
     * @return string
     */
    public function getCarrierDisplayText();

    /**
     * @param string
     * @return self
     */
    public function setCarrierDisplayText($carrierDisplayText);

    /**
     * Can be sent to OMS and/or OMS can return on response from calculation
     *
     * @return DateTime
     */
    public function getOriginalExpectedShipmentDateFrom();

    /**
     * @param  DateTime
     * @return self
     */
    public function setOriginalExpectedShipmentDateFrom(DateTime $originalExpectedShipmentDateFrom);

    /**
     * @return DateTime
     */
    public function getOriginalExpectedShipmentDateTo();

    /**
     * @param  DateTime
     * @return self
     */
    public function setOriginalExpectedShipmentDateTo(DateTime $originalExpectedShipmentDateTo);

    /**
     * The delivery date originally sent by the client
     *
     * @return DateTime
     */
    public function getOriginalExpectedDeliveryDateFrom();

    /**
     * @param  DateTime
     * @return self
     */
    public function setOriginalExpectedDeliveryDateFrom(DateTime $originalExpectedDeliveryDateFrom);

    /**
     * @return DateTime
     */
    public function getOriginalExpectedDeliveryDateTo();

    /**
     * @param  DateTime
     * @return self
     */
    public function setOriginalExpectedDeliveryDateTo(DateTime $originalExpectedDeliveryDateTo);

    /**
     * Created by the OMS and will only be present for History Detail responses.
     * This identifier is intended to be used for Customer Care price adjustments.
     *
     * @return string
     */
    public function getOmsLineId();

    /**
     * @param string
     * @return self
     */
    public function setOmsLineId($omsLineId);

    /**
     * @return ICustomerCareOrderItemTotals
     */
    public function getCustomerCareOrderItemTotals();

    /**
     * @param  ICustomerCareOrderItemTotals
     * @return self
     */
    public function setCustomerCareOrderItemTotals(ICustomerCareOrderItemTotals $customerCareOrderItemTotals);
}
