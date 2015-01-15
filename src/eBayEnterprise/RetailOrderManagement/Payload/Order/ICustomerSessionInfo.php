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

use DateInterval;
use DateTime;

interface ICustomerSessionInfo
{
    /**
     * Amount of time between when the customer session began and when the
     * order was submitted represented in hours, minutes and seconds only. Any
     * larger units (days, months, years) will be dropped.
     *
     * restrictions: optional, string matching pattern [0-9]?[0-9]:[0-9]{2}:[0-9]{2} (HH:MM:SS)
     * @return DateInterval
     */
    public function getTimeSpentOnSite();

    /**
     * @param DateInterval
     * @return self
     */
    public function setTimeSpentOnSite(DateInterval $timeSpentOnSite);

    /**
     * Last date the customer logged in, prior to the current session creating
     * the order.
     *
     * restrictions: optional, xsd:dateTime
     * @return DateTime
     */
    public function getLastLogin();

    /**
     * @param DateTime
     * @return self
     */
    public function setLastLogin(DateTime $lastLogin);

    /**
     * Password associated with the user account.
     *
     * restrictions: optional, string with length <= 50
     * @return string
     */
    public function getUserPassword();

    /**
     * @param string
     * @return self
     */
    public function setUserPassword($userPassword);

    /**
     * Time in milliseconds.
     *
     * restrictions: optional, int
     * @return int
     */
    public function getTimeOnFile();

    /**
     * @param int
     * @return self
     */
    public function setTimeOnFile($timeOnFile);

    /**
     * Transaction response code from RTC Payment Service.
     *
     * restrictions: optional
     * @return string
     */
    public function getRtcTransactionResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setRtcTransactionResponseCode($rtcTransactionResponseCode);

    /**
     * "ExtendReasonCodes" from the RTC Payment Service.
     *
     * restrictions: optional
     * @return string
     */
    public function getRtcReasonCode();

    /**
     * @param string
     * @return self
     */
    public function setRtcReasonCode($rtcReasonCode);

    /**
     * Number of times the user attempted to login.
     *
     * restrictions: optional
     * @return int
     */
    public function getAuthorizationAttempts();

    /**
     * @param int
     * @return self
     */
    public function setAuthorizationAttempts($authorizationAttempts);
}
