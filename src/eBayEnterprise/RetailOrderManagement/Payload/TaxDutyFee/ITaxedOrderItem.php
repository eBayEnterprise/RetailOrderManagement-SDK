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

interface ITaxedOrderItem extends IOrderItem, ITaxedFeeContainer, ITaxedGifting, ITaxedCustomizationContainer
{
    const MERCHANDISE_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedMerchandisePriceGroup';
    const SHIPPING_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedPriceGroup';
    const DUTY_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ITaxedDutyPriceGroup';

    /**
     * Get a new, empty price group for merchandise prices.
     *
     * @return ITaxedMerchandisePriceGroup
     */
    public function getEmptyMerchandisePriceGroup();

    /**
     * Get a new, empty price group for shipping prices.
     *
     * @return ITaxedShippingPriceGroup
     */
    public function getEmptyShippingPriceGroup();

    /**
     * Get a new, empty price group for duty prices.
     *
     * @return ITaxedDutyPriceGroup
     */
    public function getEmptyDutyPriceGroup();

    /**
     * Get the price information for the item
     *
     * @return ITaxedMerchandisePriceGroup
     */
    public function getMerchandisePricing();

    /**
     * @param ITaxedMerchandisePriceGroup
     * @return self
     */
    public function setMerchandisePricing(ITaxedMerchandisePriceGroup $priceGroup);

    /**
     * Get the item's shipping price
     *
     * restrictions: optional
     * @return ITaxedPriceGroup
     */
    public function getShippingPricing();

    /**
     * @param ITaxedPriceGroup
     * @return self
     */
    public function setShippingPricing(ITaxedPriceGroup $priceGroup);

    /**
     * Get duty pricing information
     *
     * restrictions: optional
     * @return ITaxedDutyPriceGroup
     */
    public function getDutyPricing();

    /**
     * @param ITaxedDutyPriceGroup
     * @return self
     */
    public function setDutyPricing(ITaxedDutyPriceGroup $priceGroup);
}
