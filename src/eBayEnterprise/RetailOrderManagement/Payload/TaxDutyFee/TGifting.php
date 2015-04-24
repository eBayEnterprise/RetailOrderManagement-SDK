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

use DOMXPath;

trait TGifting
{
    use TGiftingBase;

    /** @var IMerchandisePriceGroup */
    protected $giftPricing;

    /**
     * @return IMerchandisePriceGroup
     */
    public function getGiftPricing()
    {
        return $this->giftPricing;
    }

    public function setGiftPricing(IMerchandisePriceGroup $giftPricing)
    {
        $this->giftPricing = $giftPricing;
        return $this;
    }
}
