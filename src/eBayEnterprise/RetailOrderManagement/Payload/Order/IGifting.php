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

interface IGifting
{
    const GIFTING_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup';

    /**
     * Get a new, empty price group for gifting pricing.
     *
     * @return IPriceGroup
     */
    public function getEmptyGiftingPriceGroup();

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
     * Pricing data for the gift.
     *
     * @return IPriceGroup
     */
    public function getGiftPricing();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setGiftPricing(IPriceGroup $giftPricing);

    /**
     * Indicates if the gifting includes gift wrapping.
     *
     * @return bool
     */
    public function getIncludeGiftWrapping();

    /**
     * @param bool
     * @return self
     */
    public function setIncludeGiftWrapping($includeGiftWrapping);

    /**
     * Person receiving the gift.
     *
     * @return string
     */
    public function getGiftMessageTo();

    /**
     * @param string
     * @return self
     */
    public function setGiftMessageTo($giftMessageTo);

    /**
     * Person sending the gift.
     *
     * @return string
     */
    public function getGiftMessageFrom();

    /**
     * @param string
     * @return self
     */
    public function setGiftMessageFrom($giftMessageFrom);

    /**
     * Message associated with the gift.
     *
     * restrictions: optional, whitespace normalized string
     * @return string
     */
    public function getGiftMessageContent();

    /**
     * @param string
     * @return self
     */
    public function setGiftMessageContent($giftMessageContent);

    /**
     * "To" name to include on a gift card sent with the item.
     *
     * @return string
     */
    public function getGiftCardTo();

    /**
     * @param string
     * @return self
     */
    public function setGiftCardTo($giftCardTo);

    /**
     * "From" name to include on a gift card sent with the item.
     *
     * @return string
     */
    public function getGiftCardFrom();

    /**
     * @param string
     * @return self
     */
    public function setGiftCardFrom($giftCardFrom);

    /**
     * Message associated with the gift card sent with the item.
     *
     * restrictions: optional, whitespace normalized string
     * @return string
     */
    public function getGiftCardMessage();

    /**
     * @param string
     * @return self
     */
    public function setGiftCardMessage($giftCardMessage);

    /**
     * "To" name to include with a greeting message included on the packslip.
     *
     * @return string
     */
    public function getPackslipTo();

    /**
     * @param string
     * @return self
     */
    public function setPackslipTo($packslipTo);

    /**
     * "From" name to include with a greeting message included on the packslip.
     *
     * @return string
     */
    public function getPackslipFrom();

    /**
     * @param string
     * @return self
     */
    public function setPackslipFrom($packslipFrom);

    /**
     * Message associated with a greeting message included on the packslip.
     *
     * restrictions: optional, whitespace normalized string
     * @return string
     */
    public function getPackslipMessage();

    /**
     * @param string
     * @return self
     */
    public function setPackslipMessage($packslipMessage);

    /**
     * A localized translation of the "From" label to include on gift messages,
     * gift cards and packslips.
     *
     * restrictions: optional
     * @return string
     */
    public function getLocalizedFromLabel();

    /**
     * @param string
     * @return self
     */
    public function setLocalizedFromLabel($localizedFromLabel);

    /**
     * A localized translation of the "To" label to include on gift messages,
     * gift cards and packslips.
     *
     * restrictions: optional
     * @return string
     */
    public function getLocalizedToLabel();

    /**
     * @param string
     * @return self
     */
    public function setLocalizedToLabel($localizedToLabel);
}
