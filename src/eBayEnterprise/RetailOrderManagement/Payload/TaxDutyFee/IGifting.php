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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

interface IGifting
{
    const GIFTING_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup';

    /**
     * Get a new, empty price group for gifting pricing.
     *
     * @return IMerchandisePriceGroup
     */
    public function getEmptyGiftPriceGroup();

    /**
     * Unique identifier for the gift packaging
     *
     * restrictions: length <= 12
     * @return string
     */
    public function getGiftId();

    /**
     * @param string
     * @return self
     */
    public function setGiftId($id);

    /**
     * Identifier for the item being included as a gift. A SKU.
     *
     * restrictions: string with length >= 1 and <= 20
     * @return string
     */
    public function getGiftItemId();

    /**
     * @param string
     * @return self
     */
    public function setGiftItemId($giftItemId);

    /**
     * Gift packaging description
     *
     * restrictions: optional
     * @return string
     */
    public function getGiftDescription();

    /**
     * @param string
     * @return self
     */
    public function setGiftDescription($giftItemId);

    /**
     * Pricing data for the gift.
     *
     * @return IMerchandisePriceGroup
     */
    public function getGiftPricing();

    /**
     * @param IMerchandisePriceGroup
     * @return self
     */
    public function setGiftPricing(IMerchandisePriceGroup $giftPricing);
}
