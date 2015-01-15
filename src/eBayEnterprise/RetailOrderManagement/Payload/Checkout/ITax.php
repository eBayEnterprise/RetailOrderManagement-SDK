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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Order\ITax as IOrderTax;

interface ITax extends IOrderTax, IInvoiceTextCodeContainer
{
    /**
     * Amount of item amount not subject to taxes due to tax exempt status.
     *
     * @return float
     */
    public function getExemptAmount();

    /**
     * @param float
     * @return self
     */
    public function setExemptAmount($exemptAmount);

    /**
     * Amount of item amount not subject to taxes due to non-taxable status.
     *
     * @return float
     */
    public function getNonTaxableAmount();

    /**
     * @param float
     * @return self
     */
    public function setNonTaxableAmount($nonTaxableAmount);
}
