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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPhysicalAddress as ICheckoutPhysicalAddress;

interface IPhysicalAddress extends IPayload, ICheckoutPhysicalAddress
{
    /**
     * Building name of the address.
     *
     * restrictions: optional
     * @return string
     */
    public function getBuildingName();

    /**
     * @param string
     * @return self
     */
    public function setBuildingName($name);

    /**
     * Post office box of the address (do not pass PO Box as part of the request, i.e. 765).
     *
     * restrictions: optional
     * @return string
     */
    public function getPoBox();

    /**
     * @param string
     * @return self
     */
    public function setPoBox($poBox);

    /**
     * The proper state, province, or territory name of the address.
     *
     * restrictions: optional
     * @return string
     */
    public function getMainDivisionName();

    /**
     * @param string
     * @return self
     */
    public function setMainDivisionName($name);

    /**
     * The proper country name of the address.
     *
     * restrictions: optional
     * @return string
     */
    public function getCountryName();

    /**
     * @param string
     * @return self
     */
    public function setCountryName($name);
}
