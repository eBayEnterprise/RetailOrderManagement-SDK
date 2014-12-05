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

interface IReturnedItem extends IReturnAmount
{
    /**
     * Reason for the return
     *
     * @return string
     */
    public function getReason();
    /**
     * @param string
     * @return self
     */
    public function setReason($reason);
    /**
     * @return string
     */
    public function getReasonCode();
    /**
     * @param string
     * @return self
     */
    public function setReasonCode($reasonCode);
    /**
     * Items remaining in the order
     *
     * @return float
     */
    public function getRemainingQuantity();
    /**
     * @param float
     * @return self
     */
    public function setRemainingQuantity($remainingQuantity);
}
