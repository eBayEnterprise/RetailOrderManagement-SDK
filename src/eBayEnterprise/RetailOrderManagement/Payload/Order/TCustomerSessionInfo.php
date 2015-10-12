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

use DateInterval;
use DateTime;

trait TCustomerSessionInfo
{
    /** @var DateInterval */
    protected $timeSpentOnSite;
    /** @var DateTime */
    protected $lastLogin;
    /** @var string */
    protected $userPassword;
    /** @var int */
    protected $timeOnFile;
    /** @var string */
    protected $rtcTransactionResponseCode;
    /** @var string */
    protected $rtcReasonCode;
    /** @var int */
    protected $authorizationAttempts;

    public function getTimeSpentOnSite()
    {
        return $this->timeSpentOnSite;
    }

    public function setTimeSpentOnSite(DateInterval $timeSpentOnSite)
    {
        $this->timeSpentOnSite = $timeSpentOnSite;
        return $this;
    }

    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function setLastLogin(DateTime $lastLogin)
    {
        $this->lastLogin = $lastLogin;
        return $this;
    }

    public function getUserPassword()
    {
        return $this->userPassword;
    }

    public function setUserPassword($userPassword)
    {
        $this->userPassword = $this->cleanString($userPassword, 50);
        return $this;
    }

    public function getTimeOnFile()
    {
        return $this->timeOnFile;
    }

    public function setTimeOnFile($timeOnFile)
    {
        $this->timeOnFile = $timeOnFile;
        return $this;
    }

    public function getRtcTransactionResponseCode()
    {
        return $this->rtcTransactionResponseCode;
    }

    public function setRtcTransactionResponseCode($rtcTransactionResponseCode)
    {
        $this->rtcTransactionResponseCode = $rtcTransactionResponseCode;
        return $this;
    }

    public function getRtcReasonCode()
    {
        return $this->rtcReasonCode;
    }

    public function setRtcReasonCode($rtcReasonCode)
    {
        $this->rtcReasonCode = $rtcReasonCode;
        return $this;
    }

    public function getAuthorizationAttempts()
    {
        return $this->authorizationAttempts;
    }

    public function setAuthorizationAttempts($authorizationAttempts)
    {
        $this->authorizationAttempts = $authorizationAttempts;
        return $this;
    }

    /**
     * Serialize customer session info.
     *
     * @return string
     */
    protected function serializeCustomerSessionInfo()
    {
        return '<SessionInfo>'
            . $this->serializeTimeSpentOnSite()
            . $this->serializeLastLogin()
            . $this->serializeOptionalXmlEncodedValue('UserPassword', $this->getUserPassword())
            . $this->serializeOptionalXmlEncodedValue('TimeOnFile', $this->getTimeOnFile())
            . $this->serializeOptionalXmlEncodedValue('RTCTransactionResponseCode', $this->getRtcTransactionResponseCode())
            . $this->serializeOptionalXmlEncodedValue('RTCReasonCodes', $this->getRtcReasonCode())
            . $this->serializeOptionalXmlEncodedValue('AuthorizationAttempts', $this->getAuthorizationAttempts())
            . '</SessionInfo>';
    }

    /**
     * Serialize the amount of time spent on site. Use the formatted hours, minutes
     * and seconds from the date interval if it has been set. Return an empty
     * string, no serialization, otherwise.
     *
     * @return string
     */
    protected function serializeTimeSpentOnSite()
    {
        $timeOnSite = $this->getTimeSpentOnSite();
        return $timeOnSite ? "<TimeSpentOnSite>{$timeOnSite->format('%H:%I:%S')}</TimeSpentOnSite>" : '';
    }

    /**
     * Return a serialization of the last login date if one has been set. Return
     * an empty string, no serialization, otherwise.
     *
     * @return string
     */
    protected function serializeLastLogin()
    {
        $lastLogin = $this->getLastLogin();
        return $lastLogin ? "<LastLogin>{$lastLogin->format('c')}</LastLogin>" : '';
    }
}
