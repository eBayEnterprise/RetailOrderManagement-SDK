<?php
/**
 * Copyright (c) 2014-2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2014-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

interface IOrderItemRequest extends IOrderItem, IFeeContainer, IGifting, ICustomizationContainer
{
    const MERCHANDISE_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup';
    const SHIPPING_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IPriceGroup';
    const DUTY_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IDutyPriceGroup';
    const PHYSICAL_ADDRESS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IOriginPhysicalAddress';

    /**
     * Get a new, empty price group for merchandise prices.
     *
     * @return IMerchandisePriceGroup
     */
    public function getEmptyMerchandisePriceGroup();

    /**
     * Get a new, empty price group for shipping prices.
     *
     * @return IShippingPriceGroup
     */
    public function getEmptyShippingPriceGroup();

    /**
     * Get a new, empty price group for duty prices.
     *
     * @return IDutyPriceGroup
     */
    public function getEmptyDutyPriceGroup();

    /**
     * Get a new, empty physical address object.
     *
     * @return IPhysicalAddress
     */
    public function getEmptyPhysicalAddress();

    /**
     * Administrative (business) address or the address of order taking, order acceptance
     *  or place of principle negotiation or location of the Store
     *
     * @return IPhysicalAddress
     */
    public function getAdminOrigin();

    /**
     * @param IPhysicalAddress
     * @return self
     */
    public function setAdminOrigin(IPhysicalAddress $address);

    /**
     * Address from which the item is expected to ship from.
     *
     * @return IPhysicalAddress
     */
    public function getShippingOrigin();

    /**
     * @param IPhysicalAddress
     * @return self
     */
    public function setShippingOrigin(IPhysicalAddress $address);

    /**
     * Code for the country of manufacture.
     *
     * restrictions: optional
     * @return string
     */
    public function getManufacturingCountryCode();

    /**
     * @param string
     * @return self
     */
    public function setManufacturingCountryCode($code);

    /**
     * Get the price information for the item
     *
     * @return IMerchandisePriceGroup
     */
    public function getMerchandisePricing();

    /**
     * @param IMerchandisePriceGroup
     * @return self
     */
    public function setMerchandisePricing(IMerchandisePriceGroup $priceGroup);

    /**
     * Get the item's shipping price
     *
     * restrictions: optional
     * @return IPriceGroup
     */
    public function getShippingPricing();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setShippingPricing(IPriceGroup $priceGroup);

    /**
     * Get duty pricing information
     *
     * restrictions: optional
     * @return IDutyPriceGroup
     */
    public function getDutyPricing();

    /**
     * @param IDutyPriceGroup
     * @return self
     */
    public function setDutyPricing(IDutyPriceGroup $priceGroup);
}
