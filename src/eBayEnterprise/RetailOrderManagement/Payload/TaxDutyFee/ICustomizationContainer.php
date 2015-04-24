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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

interface ICustomizationContainer
{
    const CUSTOMIZATION_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\ICustomizationIterable';
    const CUSTOMIZATION_BASE_PRICE_GROUP_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\IMerchandisePriceGroup';

    /**
     * get a new, empty IMerchandisePriceGroup object
     * @return IMerchandisePriceGroup
     */
    public function getEmptyMerchandisePriceGroup();

    /**
     * Get all customizations in the container
     *
     * @return ICustomizationIterable
     */
    public function getCustomizations();

    /**
     * @param ICustomizationIterable
     * @return self
     */
    public function setCustomizations(ICustomizationIterable $iterable);

    /**
     * Base amount for all customizations
     *
     * @return IMerchandisePriceGroup
     */
    public function getCustomizationBasePricing();

    /**
     * @param IMerchandisePriceGroup
     * @return self
     */
    public function setCustomizationBasePricing(IMerchandisePriceGroup $priceGroup);
}
