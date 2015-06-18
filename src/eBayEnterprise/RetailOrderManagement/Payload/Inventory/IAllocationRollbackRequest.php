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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

/**
 * Operation is used undo a reservation for inventory for an order.
 */
interface IAllocationRollbackRequest extends IPayload, IAllocationRollbackMessage
{
    /**
     * Uniquely identifies a request operation
     *
     * restrictions: required, 1 < length <= 40
     * @return string
     */
    public function getRequestId();

    /**
     * @param string
     * @return self
     */
    public function setRequestId($requestId);

    /**
     * Identifies the inventory reservation which is created by this operation.
     *
     * restrictions: required, length 40
     * @return string
     */
    public function getReservationId();

    /**
     * @param string
     * @return self
     */
    public function setReservationId($reservationId);
}
