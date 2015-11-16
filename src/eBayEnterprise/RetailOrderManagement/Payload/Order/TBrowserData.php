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

trait TBrowserData
{
    /** @var string */
    protected $hostname;
    /** @var string */
    protected $ipAddress;
    /** @var string */
    protected $sessionId;
    /** @var string */
    protected $userAgent;
    /** @var string */
    protected $connection;
    /** @var string */
    protected $cookies;
    /** @var string */
    protected $userCookie;
    /** @var string */
    protected $userAgentOs;
    /** @var string */
    protected $userAgentCpu;
    /** @var string */
    protected $headerFrom;
    /** @var string */
    protected $webBrowserName;
    /** @var string */
    protected $javascriptData;
    /** @var string */
    protected $referrer;
    /** @var string */
    protected $contentTypes;
    /** @var string */
    protected $encoding;
    /** @var string */
    protected $language;
    /** @var string */
    protected $charSet;

    public function getHostname()
    {
        return $this->hostname;
    }

    public function setHostname($hostname)
    {
        $this->hostname = $this->cleanString($hostname, 50);
        return $this;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $this->isValidIpAddress($ipAddress) ? $ipAddress : null;
        return $this;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $this->cleanString($sessionId, 255);
        return $this;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $this->cleanString($userAgent, 4096);
        return $this;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function setConnection($connection)
    {
        $this->connection = $this->cleanString($connection, 25);
        return $this;
    }

    public function getCookies()
    {
        return $this->cookies;
    }

    public function setCookies($cookies)
    {
        $this->cookies = $this->normalizeWhitespace($cookies);
        return $this;
    }

    public function getUserCookie()
    {
        return $this->userCookie;
    }

    public function setUserCookie($userCookie)
    {
        $this->userCookie = $this->cleanString($userCookie, 50);
        return $this;
    }

    public function getUserAgentOs()
    {
        return $this->userAgentOs;
    }

    public function setUserAgentOs($userAgentOs)
    {
        $this->userAgentOs = $this->cleanString($userAgentOs, 100);
        return $this;
    }

    public function getUserAgentCpu()
    {
        return $this->userAgentCpu;
    }

    public function setUserAgentCpu($userAgentCpu)
    {
        $this->userAgentCpu = $this->cleanString($userAgentCpu, 100);
        return $this;
    }

    public function getHeaderFrom()
    {
        return $this->headerFrom;
    }

    public function setHeaderFrom($headerFrom)
    {
        $this->headerFrom = $this->cleanString($headerFrom, 100);
        return $this;
    }

    public function getWebBrowserName()
    {
        return $this->webBrowserName;
    }

    public function setWebBrowserName($webBrowserName)
    {
        $this->webBrowserName = $this->cleanString($webBrowserName, 100);
        return $this;
    }

    public function getJavascriptData()
    {
        return $this->javascriptData;
    }

    public function setJavascriptData($javascriptData)
    {
        $this->javascriptData = $this->normalizeWhitespace($javascriptData);
        return $this;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function setReferrer($referrer)
    {
        $this->referrer = $this->cleanString($referrer, 1024);
        return $this;
    }

    public function getContentTypes()
    {
        return $this->contentTypes;
    }

    public function setContentTypes($contentTypes)
    {
        $this->contentTypes = $this->cleanString($contentTypes, 1024);
        return $this;
    }

    public function getEncoding()
    {
        return $this->encoding;
    }

    public function setEncoding($encoding)
    {
        $this->encoding = $this->cleanString($encoding, 50);
        return $this;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $this->cleanString($language, 255);
        return $this;
    }

    public function getCharSet()
    {
        return $this->charSet;
    }

    public function setCharSet($charSet)
    {
        $this->charSet = $this->cleanString($charSet, 50);
        return $this;
    }

    /**
     * Serialize browser data associated with the order.
     *
     * @return string
     */
    protected function serializeBrowserData()
    {
        return '<BrowserData>'
            . "<HostName>{$this->xmlEncode($this->getHostname())}</HostName>"
            . "<IPAddress>{$this->xmlEncode($this->getIpAddress())}</IPAddress>"
            . '<SessionId>' . $this->xmlEncode($this->getSessionId()) . '</SessionId>'
            . '<UserAgent>' . $this->xmlEncode($this->getUserAgent()) . '</UserAgent>'
            . $this->serializeOptionalXmlEncodedValue('Connection', $this->getConnection())
            . $this->serializeOptionalXmlEncodedValue('Cookies', $this->getCookies())
            . $this->serializeOptionalXmlEncodedValue('UserCookie', $this->getUserCookie())
            . $this->serializeOptionalXmlEncodedValue('UserAgentOS', $this->getUserAgentOs())
            . $this->serializeOptionalXmlEncodedValue('UserAgentCPU', $this->getUserAgentCpu())
            . $this->serializeOptionalXmlEncodedValue('HeaderFrom', $this->getHeaderFrom())
            . $this->serializeOptionalXmlEncodedValue('EmbeddedWebBrowserFrom', $this->getWebBrowserName())
            . '<JavascriptData>' . $this->xmlEncode($this->getJavascriptData()) . '</JavascriptData>'
            . '<Referrer>' . $this->xmlEncode($this->getReferrer()) . '</Referrer>'
            . "<HTTPAcceptData>"
            . '<ContentTypes>' . $this->xmlEncode($this->getContentTypes()) . '</ContentTypes>'
            . '<Encoding>' . $this->xmlEncode($this->getEncoding()) . '</Encoding>'
            . "<Language>{$this->xmlEncode($this->getLanguage())}</Language>"
            . '<CharSet>' . $this->xmlEncode($this->getCharSet()) . '</CharSet>'
            . "</HTTPAcceptData>"
            . '</BrowserData>';
    }

    /**
     * Test for an IP address to be a valid IP address. Currently, requires
     * address to be valid IPv4 addresses only.
     *
     * @param string
     * @return bool
     */
    protected function isValidIpAddress($ipAddress)
    {
        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
    }

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
