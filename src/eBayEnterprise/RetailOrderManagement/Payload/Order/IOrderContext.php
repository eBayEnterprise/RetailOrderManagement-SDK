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

use DateTime;

interface IOrderContext extends IBrowserData, ICustomerSessionInfo, IPayPalPayerInfo, IOrderContextCustomAttributeContainer
{
    /**
     * Time when fraud prevention data was captured.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getTdlOrderTimestamp();

    /**
     * business requirement: Millisecond precision
     * @param DateTime
     * @return self
     */
    public function setTdlOrderTimestamp(DateTime $tdlOrderTimestamp);
}
