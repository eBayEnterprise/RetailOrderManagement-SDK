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
 * Order line item in a checkout inventory API inventory details request or allocation request.
 */
interface IInStorePickUpItem extends IOrderItem, IAddress
{
    /**
     * This is the identifier of the store in which the line item will be picked up.
     *
     * restrictions: length <= 100
     * @return string
     */
    public function getStoreFrontId();

    /**
     * @param string
     * @return self
     */
    public function setStoreFrontId($id);

    /**
     * Store Name
     *
     * restrictions: length <= 100
     * @return string
     */
    public function getStoreFrontName();

    /**
     * @param string
     * @return self
     */
    public function setStoreFrontName($name);
}
