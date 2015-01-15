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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPhysicalAddress;

/**
 * A proxy pickup specifies an alternate person allowed to pick-up an item
 * from a retail store location when using in-store pick-up or ship-to-store
 * fulfillment.
 */
interface IProxyPickupDetails extends IPayload, IPhysicalAddress
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Full name of the person. This field should only be used when separate
     * first and last names are not available.
     *
     * restrictions: optional, required if separate first and last names are not available
     * @return string
     */
    public function getFullName();

    /**
     * @param string
     * @return self
     */
    public function setFullName($fullName);

    /**
     * First name of the person allowed to pick-up the item.
     *
     * restrictions: optional, required when full name is not provided
     * @return string
     */
    public function getFirstName();

    /**
     * @param string
     * @return self
     */
    public function setFirstName($firstName);

    /**
     * Last name of the person allowed to pick-up the item.
     *
     * restrictions: optional, required if a full name is not provided
     * @return string
     */
    public function getLastName();

    /**
     * @param string
     * @return self
     */
    public function setLastName($lastName);

    /**
     * Email address of the person allowed to pick-up the item.
     *
     * restrictions: optional
     * @return string
     */
    public function getEmail();

    /**
     * @param string
     * @return self
     */
    public function setEmail($email);

    /**
     * Phone number for the person making the pick-up.
     *
     * restrictions: optional
     * @return string
     */
    public function getPhone();

    /**
     * @param string
     * @return self
     */
    public function setPhone($phone);

    /**
     * Personal relationship of the person making the pick-up to the person
     * making the purchase, for example, Sister, Friend, or Spouse.
     *
     * restrictions: optional
     * @return string
     */
    public function getRelationship();

    /**
     * @param string
     * @return self
     */
    public function setRelationship($relationship);
}
