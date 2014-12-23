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

interface IReturnSummary
{
    /**
     * Return reference number
     *
     * @return string
     */
    public function getReferenceNumber();
    /**
     * @param string
     * @return self
     */
    public function setReferenceNumber($referenceNumber);
    /**
     * Total amount credited for the return
     *
     * @return float
     */
    public function getTotalCredit();
    /**
     * @param float
     * @return self
     */
    public function setTotalCredit($totalCredit);
    /**
     * @TODO: The usage of this element is unclear and needs better definition.
     *
     * @return string
     */
    public function getReturnOrCredit();
    /**
     * @param string
     * @return self
     */
    public function setReturnOrCredit($returnOrCredit);
    /**
     * @return bool
     */
    public function isReturn();

    /**
     * @return bool
     */
    public function isCredit();
}
