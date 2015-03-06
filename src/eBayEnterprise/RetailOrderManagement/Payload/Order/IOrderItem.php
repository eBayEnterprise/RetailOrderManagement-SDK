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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IIdentity;

interface IOrderItem extends IPayload, IIdentity, IFeeContainer, IEstimatedDeliveryDate, IGifting, IItemCustomization, ICustomAttributeContainer, INamedDeliveryDate
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup';
    const STORE_FRONT_DETAILS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IStoreFrontDetails';
    const PROXY_PICKUP_DETAILS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IProxyPickupDetails';
    // Acceptable fulfillment channel values.
    const FULFILLMENT_CHANNEL_SHIP_TO_STORE = 'SHIP_TO_STORE';
    const FULFILLMENT_CHANNEL_STORE_PICK_UP = 'STORE_PICK_UP';
    const FULFILLMENT_CHANNEL_SHIP_TO_HOME = 'SHIP_TO_HOME';
    const FULFILLMENT_CHANNEL_STORE_RESERVATION = 'STORE_RESERVATION';
    // Acceptable Tax and Duty Display Types
    const TAX_AND_DUTY_DISPLAY_CONSOLIDATED = 'Consolidated Taxes Only';
    const TAX_AND_DUTY_DISPLAY_INDIVIDUAL = 'Individual Taxes Only';
    const TAX_AND_DUTY_DISPLAY_SINGLE_AMOUNT = 'Taxes and Duties Consolidated in a Single Amount';
    const TAX_AND_DUTY_DISPLAY_NONE = 'No Taxes or Duties';

    /**
     * Get a new, empty price group for merchandise, shipping and duty prices.
     *
     * @return IPriceGroup
     */
    public function getEmptyPriceGroup();

    /**
     * Get a new, empty store front details payload.
     *
     * @return IStoreFrontDetails
     */
    public function getEmptyStoreFrontDetails();

    /**
     * Get a new, empty proxy pickup details payload.
     *
     * @return IProxyPickupDetails
     */
    public function getEmptyProxyPickupDetails();

    /**
     * Web order line number assigned by the webstore.
     *
     * restrictions: 3 digit, positive integer
     * @return int
     */
    public function getLineNumber();

    /**
     * @param int
     * @return self
     */
    public function setLineNumber($lineNumber);

    /**
     * Indicates if the line item is a hidden gift.
     *
     * restrictions: optional
     * @return bool
     */
    public function getIsHiddenGift();

    /**
     * @param bool
     * @return self
     */
    public function setIsHiddenGift($isHiddenGift);

    /**
     * Line item control of tax and duty display. May be one of:
     * - Consolidated Taxes Only: A single tax line is displayed with no duty
     *   lines displayed.
     * - Individual Taxes Only: One or more tax lines are displayed based on taxes
     *   returned by the tax service with no duty lines displayed.
     * - No Taxes or Duties: No taxes or duties are being charged as part of the
     *   order but may be responsible for payment of taxes and duties upon delivery.
     * - Taxes and Duties Consolidated in a Single Amount: All taxes and duties
     *   are consolidated into a single amount.
     *
     * restrictions: optional, string
     * @return string
     */
    public function getTaxAndDutyDisplayType();

    /**
     * @param string
     * @return self
     */
    public function setTaxAndDutyDisplayType($taxAndDutyDisplayType);

    /**
     * Subscription id for the item.
     *
     * restrictions: int >= 1 and <= 999
     * @return int
     */
    public function getSubscriptionId();

    /**
     * @param int
     * @return self
     */
    public function setSubscriptionId($subscriptionId);

    /**
     * Unique id used to identify the item. A SKU.
     *
     * restrictions: string with length >= 1 and <= 20
     * @return string
     */
    public function getItemId();

    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId);

    /**
     * Quantity of the item ordered.
     *
     * @return float
     */
    public function getQuantity();

    /**
     * @param float
     * @return self
     */
    public function setQuantity($quantity);

    /**
     * Customer facing description of the item.
     *
     * @return string
     */
    public function getDescription();

    /**
     * @param string
     * @return self
     */
    public function setDescription($description);

    /**
     * Descriptive name of the color of the item.
     *
     * restrictions: optional
     * @return string
     */
    public function getColor();

    /**
     * @param string
     * @return self
     */
    public function setColor($color);

    /**
     * Identifier for the color of an item.
     *
     * restrictions: required if "color" is set
     * @return string
     */
    public function getColorId();

    /**
     * @param string
     * @return self
     */
    public function setColorId($colorId);

    /**
     * Descriptive name for the size of an item.
     *
     * restrictions: optional
     * @return string
     */
    public function getSize();

    /**
     * @param string
     * @return self
     */
    public function setSize($size);

    /**
     * Identifier for the size of an item.
     *
     * restrictions: required if size is set
     * @return string
     */
    public function getSizeId();

    /**
     * @param string
     * @return self
     */
    public function setSizeId($sizeId);

    /**
     * Size of item screen. Used for EHF Environment Handling Fee calculations.
     *
     * restrictions: optional
     * @return string
     */
    public function getScreenSize();

    /**
     * @param string
     * @return self
     */
    public function setScreenSize($screenSize);

    /**
     * Identifier for the department the item belongs to.
     *
     * restrictions: optional
     * @return string
     */
    public function getDepartment();

    /**
     * @param string
     * @return self
     */
    public function setDepartment($department);

    /**
     * Get merchandise pricing data.
     *
     * @return IPriceGroup
     */
    public function getMerchandisePricing();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setMerchandisePricing(IPriceGroup $merchandisePricing);

    /**
     * Get shipping pricing data.
     *
     * restrictions: optional
     * @return IPriceGroup
     */
    public function getShippingPricing();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setShippingPricing(IPriceGroup $shippingPricing);

    /**
     * Get duty pricing data.
     *
     * restrictions: optional
     * @return IPriceGroup
     */
    public function getDutyPricing();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setDutyPricing(IPriceGroup $dutyPricing);

    /**
     * Shipping program for ShopRunner integrations.
     *
     * restrictions: optional
     * @return string
     */
    public function getShippingProgram();

    /**
     * @param string
     * @return self
     */
    public function setShippingProgram($shippingProgram);

    /**
     * Shipping program auth token used for ShopRunner integrations.
     *
     * restrictions: optional
     * @return string
     */
    public function getShippingProgramAuthToken();

    /**
     * @param string
     * @return self
     */
    public function setShippingProgramAuthToken($shippingProgramAuthToken);

    /**
     * Type of shipping used to deliver the order item.
     *
     * restrictions: optional
     * @return string
     */
    public function getShippingMethod();

    /**
     * @param string
     * @return self
     */
    public function setShippingMethod($shippingMethod);

    /**
     * Customer facing text for the shipping method.
     *
     * restrictions: optional
     * @return string
     */
    public function getShippingMethodDisplayText();

    /**
     * @param string
     * @return self
     */
    public function setShippingMethodDisplayText($shippingMethodDisplayText);

    /**
     * Brick-and-mortar store the item is shipped to/available at for in-store pick-up.
     *
     * @return IStoreFrontDetails
     */
    public function getStoreFrontDetails();

    /**
     * @param IStoreFrontDetails
     * @return self
     */
    public function setStoreFrontDetails(IStoreFrontDetails $storeFrontDetails);

    /**
     * Get the person specified as an alternate person allowed to pick up an
     * item from a retail store location.
     *
     * @return IProxyPickupDetails
     */
    public function getProxyPickupDetails();

    /**
     * @param IProxyPickupDetails
     * @return self
     */
    public function setProxyPickupDetails(IProxyPickupDetails $proxyPickupDetails);

    /**
     * Fulfillment channel to use to complete the order.
     *
     * restrictions: optional, one of "SHIP_TO_STORE", "STORE_PICK_UP", "SHIP_TO_HOME", "STORE_RESERVATION"
     * @return string
     */
    public function getFulfillmentChannel();

    /**
     * @param string
     * @return self
     */
    public function setFulfillmentChannel($fulfillmentChannel);

    /**
     * Any additional deliver instructions specified by the customer for the delivery.
     *
     * restrictions: optional, whitespace normalized string
     * @return string
     */
    public function getDeliveryInstructions();

    /**
     * @param string
     * @return self
     */
    public function setDeliveryInstructions($deliveryInstructions);

    /**
     * Unique id used by the vendor of the product.
     *
     * restrictions: optional
     * @return string
     */
    public function getVendorId();

    /**
     * @param string
     * @return self
     */
    public function setVendorId($vendorId);

    /**
     * Name of the vendor of the product.
     *
     * restrictions: optional
     * @return string
     */
    public function getVendorName();

    /**
     * @param string
     * @return self
     */
    public function setVendorName($vendorName);

    /**
     * Promotional message to display to the user.
     *
     * restrictions: optional, whitespace normalized string
     * @return string
     */
    public function getShopRunnerMessage();

    /**
     * @param string
     * @return self
     */
    public function setShopRunnerMessage($shopRunnerMessage);

    /**
     * Product serial number.
     *
     * restrictions: optional
     * @return string
     */
    public function getSerialNumber();

    /**
     * @param string
     * @return self
     */
    public function setSerialNumber($serialNumber);

    /**
     * URL used by the OMS to cancel orders involving gift registry items.
     *
     * restrictions: optional
     * @return string
     */
    public function getGiftRegistryCancelUrl();

    /**
     * @param string
     * @return self
     */
    public function setGiftRegistryCancelUrl($giftRegistryCancelUrl);

    /**
     * Inventory reservation id provided by the inventory service.
     *
     * restrictions: string with length >= 1 and <= 40
     * @return string
     */
    public function getReservationId();

    /**
     * @param string
     * @return self
     */
    public function setReservationId($reservationId);
}
