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

interface IItemCustomization extends ICustomizationContainer
{
    const CUSTOMIZATION_BASE_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\IPriceGroup';

    /**
     * Get a new, empty customization price group for customization pricing.
     *
     * @return IPriceGroup
     */
    public function getEmptyCustomizationBasePrice();

    /**
     * Pricing information for the customizations.
     *
     * restrictions: optional
     * @return IPriceGroup
     */
    public function getCustomizationBasePrice();

    /**
     * @param IPriceGroup
     * @return self
     */
    public function setCustomizationBasePrice(IPriceGroup $customizationBasePrice);

    /**
     * URL to view the details of the customization.
     *
     * restrictions: optional, valid URI
     * @return string
     */
    public function getCustomizationDisplayUrl();

    /**
     * @param string
     * @return self
     */
    public function setCustomizationDisplayUrl($customizationDisplayUrl);
}
