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
 * Response to a request to reserve inventory for an order
 */
interface IAllocationReply extends IPayload, IAllocationMessage, IAllocatedItemContainer
{
    const ROOT_NODE = 'AllocationResponseMessage';

    /**
     * Identifies the inventory reservation which is created by this operation.
     *
     * restrictions: optional
     * @return string
     */
    public function getReservationId();

    /**
     * @param string
     * @return self
     */
    public function setReservationId($reservationId);
}
