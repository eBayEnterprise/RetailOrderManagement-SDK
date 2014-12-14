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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IDeliveryWindow extends IPayload
{
    /**
     * Allowable Values: ISO 8601 standard with offset from UTC in datetime data format
     * which is yyyy-mm-ddTHH:mm:ss-hh:mm for e.g. 2012-01-11T14:19:05-06:00.
     *
     * @return DateTime
     */
    public function getFrom();

    /**
     * @param string
     * @return self
     */
    public function setFrom($from);

    /**
     * Allowable Values: ISO 8601 standard with offset from UTC in datetime data format
     * which is yyyy-mm-ddTHH:mm:ss-hh:mm for e.g. 2012-01-11T14:19:05-06:00.
     *
     * @return DateTime
     */
    public function getTo();

    /**
     * @param string
     * @return self
     */
    public function setTo($to);
}
