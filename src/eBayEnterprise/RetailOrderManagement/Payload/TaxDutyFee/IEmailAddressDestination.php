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

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPersonName as ICheckoutPersonName;

interface IEmailAddressDestination extends IDestination, ICheckoutPersonName
{
    /**
     * Custom email address to be used as a destination for delivery.
     *
     * restrictions: string with length >= 1 and <= 70
     * @return string
     */
    public function getEmailAddress();

    /**
     * @param string
     * @return self
     */
    public function setEmailAddress($emailAddress);
}
