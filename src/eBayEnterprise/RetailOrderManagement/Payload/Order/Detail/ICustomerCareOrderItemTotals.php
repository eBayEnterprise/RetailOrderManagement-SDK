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

interface ICustomerCareOrderItemTotals extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'CustomerCareOrderItemTotals';

    /**
     * @return float
     */
    public function getCharges();

    /**
     * @param  float
     * @return self
     */
    public function setCharges($charges);

    /**
     * @return float
     */
    public function getDiscount();

    /**
     * @param  float
     * @return self
     */
    public function setDiscount($discount);

    /**
     * @return float
     */
    public function getExtendedPrice();

    /**
     * @param  float
     * @return self
     */
    public function setExtendedPrice($extendedPrice);

    /**
     * @return float
     */
    public function getLineTotal();

    /**
     * @param  float
     * @return self
     */
    public function setLineTotal($lineTotal);

    /**
     * @return float
     */
    public function getLineTotalWithoutTax();

    /**
     * @param  float
     * @return self
     */
    public function setLineTotalWithoutTax($lineTotalWithoutTax);

    /**
     * @return float
     */
    public function getPricingQty();

    /**
     * @param  float
     * @return self
     */
    public function setPricingQty($pricingQty);

    /**
     * @return float
     */
    public function getShippingCharges();

    /**
     * @param  float
     * @return self
     */
    public function setShippingCharges($shippingCharges);

    /**
     * @return float
     */
    public function getShippingDiscount();

    /**
     * @param  float
     * @return self
     */
    public function setShippingDiscount($shippingDiscount);

    /**
     * @return float
     */
    public function getShippingTotal();

    /**
     * @param  float
     * @return self
     */
    public function setShippingTotal($shippingTotal);

    /**
     * @return float
     */
    public function getTax();

    /**
     * @param  float
     * @return self
     */
    public function setTax($tax);

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
     * @return string
     */
    public function getMinLineStatus();

    /**
     * @param  string
     * @return self
     */
    public function setMinLineStatus($minLineStatus);

    /**
     * @return string
     */
    public function getMinLineStatusDescription();

    /**
     * @param  string
     * @return self
     */
    public function setMinLineStatusDescription($minLineStatusDescription);

    /**
     * @return string
     */
    public function getMaxLineStatus();

    /**
     * @param  string
     * @return self
     */
    public function setMaxLineStatus($maxLineStatus);

    /**
     * @return string
     */
    public function getMaxLineStatusDescription();

    /**
     * @param  string
     * @return self
     */
    public function setMaxLineStatusDescription($maxLineStatusDescription);
}
