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
use DOMXPath;

trait TOrderContext
{
    use TBrowserData, TCustomerSessionInfo, TPayPalPayerInfo, TOrderContextCustomAttributeContainer;

    /** @var DateTime */
    protected $tdlOrderTimestamp;

    public function getTdlOrderTimestamp()
    {
        return $this->tdlOrderTimestamp;
    }

    /**
     * The fraud system requires millisecond precision.
     *
     * @param DateTime
     * @return self
     */
    public function setTdlOrderTimestamp(DateTime $tdlOrderTimestamp)
    {
        $this->tdlOrderTimestamp = $tdlOrderTimestamp;
        return $this;
    }

    /**
     * Get property => XPath mapping for properties to be extracted from
     * serialized payloads for Order Context properties.
     *
     * @return array
     */
    protected function getOrderContextExtractionPaths()
    {
        return [
            'hostname' => 'string(x:Context/x:BrowserData/x:HostName)',
            'ipAddress' => 'string(x:Context/x:BrowserData/x:IPAddress)',
            'sessionId' => 'string(x:Context/x:BrowserData/x:SessionId)',
            'userAgent' => 'string(x:Context/x:BrowserData/x:UserAgent)',
            'javascriptData' => 'string(x:Context/x:BrowserData/x:JavascriptData)',
            'referrer' => 'string(x:Context/x:BrowserData/x:Referrer)',
            'contentTypes' => 'string(x:Context/x:BrowserData/x:HTTPAcceptData/x:ContentTypes)',
            'encoding' => 'string(x:Context/x:BrowserData/x:HTTPAcceptData/x:Encoding)',
            'language' => 'string(x:Context/x:BrowserData/x:HTTPAcceptData/x:Language)',
            'charSet' => 'string(x:Context/x:BrowserData/x:HTTPAcceptData/x:CharSet)',
        ];
    }

    /**
     * Get property => XPath mapping for optional properties to be extracted from
     * serialized payloads for Order Context properties.
     *
     * @return array
     */
    protected function getOrderContextOptionalExtractionPaths()
    {
        return [
            'connection' => 'x:Context/x:BrowserData/x:Connection',
            'cookies' => 'x:Context/x:BrowserData/x:Cookies',
            'userCookie' => 'x:Context/x:BrowserData/x:UserCookie',
            'userAgentOs' => 'x:Context/x:BrowserData/x:UserAgentOS',
            'userAgentCpu' => 'x:Context/x:BrowserData/x:UserAgentCPU',
            'headerFrom' => 'x:Context/x:BrowserData/x:HeaderFrom',
            'webBrowserName' => 'x:Context/x:BrowserData/x:EmbeddedWebBrowserFrom',
            'userPassword' => 'x:Context/x:SessionInfo/x:UserPassword',
            'timeOnFile' => 'x:Context/x:SessionInfo/x:TimeOnFile',
            'rtcTransactionResponseCode' => 'x:Context/x:SessionInfo/x:RTCTransactionResponseCode',
            'rtcReasonCode' => 'x:Context/x:SessionInfo/x:RTCReasonCodes',
            'authorizationAttempts' => 'x:Context/x:SessionInfo/x:AuthorizationAttempts',
            'payPalPayerId' => 'x:Context/x:PayPalPayerInfo/x:PayPalPayerID',
            'payPalPayerStatus' => 'x:Context/x:PayPalPayerInfo/x:PayPalPayerStatus',
            'payPalAddressStatus' => 'x:Context/x:PayPalPayerInfo/x:PayPalAddressStatus',
        ];
    }

    /**
     * Get property => XPath mapping for subpayload properties to be extracted from
     * serialized payloads for Order Context properties.
     *
     * @return array
     */
    protected function getOrderContextSubpayloadExtractionPaths()
    {
        return [
            'orderContextCustomAttributes' => 'x:Context/x:CustomAttributes',
        ];
    }

    /**
     * Build an XML serialization of the order context - browser data, tdl order
     * timestamp, session info, PayPal payer info and custom attributes.
     *
     * @return string
     */
    protected function serializeOrderContext()
    {
        return '<Context>'
            . $this->serializeBrowserData()
            . $this->serializeTdlOrderTimestamp()
            . $this->serializeCustomerSessionInfo()
            . $this->serializePayPalPayerInfo()
            . $this->getOrderContextCustomAttributes()->serialize()
            . '</Context>';
    }

    /**
     * Serialize the TDL order timestamp into an XML node with an ISO8601 time format with millisecond precision.
     *
     * @return string
     */
    protected function serializeTdlOrderTimestamp()
    {
        $timestamp = $this->getTdlOrderTimestamp();
        if (!$timestamp) {
            return '';
        }
        $date = $timestamp->format('Y-m-d');
        $time = $timestamp->format('H:i:s');
        $milliseconds = sprintf('%03d', $timestamp->format('u'));
        $zone = $timestamp->format('P');
        return "<TdlOrderTimestamp>{$date}T{$time}.{$milliseconds}{$zone}</TdlOrderTimestamp>";
    }

    /**
     * Deserialize order context data that is not represented by primitive types,
     * e.g. DateTime objects, DateInterval objects.
     *
     * @param DOMXPath
     * @return self
     */
    protected function deserializeExtraOrderContext(DOMXPath $xpath)
    {
        $dateTimeProperties = [
            'tdlOrderTimestamp' => 'string(x:Context/x:TdlOrderTimestamp)',
            'lastLogin' => 'string(x:Context/x:SessionInfo/x:LastLogin)',
        ];
        foreach ($dateTimeProperties as $property => $extractPath) {
            $value = $xpath->evaluate($extractPath);
            $this->$property = $value ? new DateTime($value) : null;
        }
        $timeOnSiteValue = $xpath->evaluate('string(x:Context/x:SessionInfo/x:TimeSpentOnSite)');
        $this->timeSpentOnSite = $timeOnSiteValue
            ? new DateInterval($this->convertHMSToDateInterval($timeOnSiteValue))
            : null;
        return $this;
    }

    /**
     * Convert a string of HH:MM:SS to a date interval spec string.
     *
     * @param string
     * @return string
     */
    protected function convertHMSToDateInterval($hmsTime)
    {
        $parts = explode(':', $hmsTime);
        return 'PT' . $parts[0] . 'H' . $parts[1] . 'M' . $parts[2] . 'S';
    }
}
