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

interface IOrderCreateReply extends IOrderCreate
{
    const STATUS_FAIL = 'Fail';
    const STATUS_SUCCESS = 'Success';
    const STATUS_TIMEOUT = 'Timeout';

    /**
     * Status of the created order.
     *
     * restriction: Fail, Success, Timeout
     * @return string
     */
    public function getStatus();

    /**
     * @param string
     * @return self
     */
    public function setStatus($status);

    /**
     * If an order create fails, may contain a description of the failure.
     *
     * @return string
     */
    public function getDescription();

    /**
     * @param string
     * @return self
     */
    public function setDescription($description);
}
