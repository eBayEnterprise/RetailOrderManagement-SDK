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
use eBayEnterprise\RetailOrderManagement\Payload\Order\ITax;

interface ITaxCharge extends ICharge
{
    const TAX_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITax';

    /**
     * @return string
     */
    public function getTaxType();

    /**
     * @param  string
     * @return self
     */
    public function setTaxType($taxType);

    /**
     * @return ITax
     */
    public function getTax();

    /**
     * @param ITax
     * @return self
     */
    public function setTax(ITax $tax);
}
