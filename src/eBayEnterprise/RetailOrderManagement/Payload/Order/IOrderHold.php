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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IOrderHold extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * The nature of the delay or hold.
     *
     * @return string
     */
    public function getType();

    /**
     * @param string
     * @return self
     */
    public function setType($type);

    /**
     * Date and time the hold began.
     *
     * restrictions: xsd:dateTime
     * @return DateTime
     */
    public function getHoldDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setHoldDate(DateTime $holdDate);

    /**
     * Reason the hold was put into place.
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
     * Identifier for the person who resolved the hold.
     *
     * restrictions: optional
     * @return string
     */
    public function getResolverUserId();

    /**
     * @param string
     * @return self
     */
    public function setResolverUserId($resolverUserId);

    /**
     * Details of the hold's state.
     *
     * @return string
     */
    public function getStatusDescription();

    /**
     * @param string
     * @return self
     */
    public function setStatusDescription($statusDescription);
}
