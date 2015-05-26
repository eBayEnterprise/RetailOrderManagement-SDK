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

interface ICharge extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'Charge';

    /**
     * @return string
     */
    public function getName();

    /**
     * @param  string
     * @return self
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getCategory();

    /**
     * @param  string
     * @return self
     */
    public function setCategory($category);

    /**
     * @return bool
     */
    public function getIsDiscount();

    /**
     * @param  bool
     * @return self
     */
    public function setIsDiscount($isDiscount);

    /**
     * @return bool
     */
    public function getIsPromotion();

    /**
     * @param  bool
     * @return self
     */
    public function setIsPromotion($isPromotion);

    /**
     * @return string
     */
    public function getInformational();

    /**
     * @param  string
     * @return self
     */
    public function setInformational($informational);

    /**
     * @return float
     */
    public function getUnitPrice();

    /**
     * @param  float
     * @return self
     */
    public function setUnitPrice($unitPrice);

    /**
     * @return float
     */
    public function getLinePrice();

    /**
     * @param  float
     * @return self
     */
    public function setLinePrice($linePrice);

    /**
     * @return float
     */
    public function getAmount();

    /**
     * @param  float
     * @return self
     */
    public function setAmount($amount);
}
