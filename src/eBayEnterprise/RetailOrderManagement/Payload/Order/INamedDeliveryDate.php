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

interface INamedDeliveryDate
{
    /**
     * The specified date when the delivery will occur. Used when the delivery
     * date is known. Only the date of this value is used for determining
     * the delivery date. E.g. new DateTime('2015-02-12') can be
     * considered the same as new DateTime('2015-02-12T17:32:12');
     *
     * restrictions: optional, xsd:date
     * @return DateTime
     */
    public function getNamedDeliveryDate();

    /**
     * @param DateTime
     * @return self
     */
    public function setNamedDeliveryDate(DateTime $namedDeliveryDate);

    /**
     * Start time of the delivery window. Only the time part of the DateTime
     * object is referenced to determine the delivery window start time. E.g.,
     * new DateTime('12:00:00') can be considered the same as
     * new DateTime('2014-04-15T12:00:00').
     *
     * restrictions: optional, xsd:time
     * @return DateTime
     */
    public function getNamedDeliveryTimeWindowStart();

    /**
     * @param DateTime
     * @return self
     */
    public function setNamedDeliveryTimeWindowStart(DateTime $namedDeliveryTimeWindowStart);

    /**
     * End time of the delivery window. Only the time part of the DateTime
     * object is reference to determine the delivery window end time. E.g.,
     * new DateTime('12:00:00') can be considered the same as
     * new DateTime('2014-04-15T12:00:00').
     *
     * restrictions: optional, xsd:time
     * @return DateTime
     */
    public function getNamedDeliveryTimeWindowEnd();

    /**
     * @param DateTime
     * @return self
     */
    public function setNamedDeliveryTimeWindowEnd(DateTime $namedDeliveryTimeWindowEnd);

    /**
     * Specific delivery message associated with the named deliver date.
     *
     * restrictions: required if any of the other named deliver date values are set
     * @return string
     */
    public function getNamedDeliveryMessage();

    /**
     * @param string
     * @return self
     */
    public function setNamedDeliveryMessage($namedDeliveryMessage);
}
