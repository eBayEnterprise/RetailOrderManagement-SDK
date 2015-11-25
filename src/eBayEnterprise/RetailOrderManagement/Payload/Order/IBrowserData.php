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

interface IBrowserData
{
    /**
     * The fully qualified name of the client that sent the request when the
     * order was taken through an HTTP client.
     *
     * restrictions: string with length <= 50
     * @return string
     */
    public function getHostname();

    /**
     * @param string
     * @return self
     */
    public function setHostname($hostname);

    /**
     * IP address of the client that sent the request.
     *
     * restrictions: IPv4 address
     * @return string
     */
    public function getIpAddress();

    /**
     * @param string
     * @return self
     */
    public function setIpAddress($ipAddress);

    /**
     * Customer's unique HTTP session id.
     *
     * restrictions: string with length <= 255
     * @return string
     */
    public function getSessionId();

    /**
     * @param string
     * @return self
     */
    public function setSessionId($sessionId);

    /**
     * User agent string of the client that submitted the order.
     *
     * restrictions: string with length <= 4096
     * @return string
     */
    public function getUserAgent();

    /**
     * @param string
     * @return self
     */
    public function setUserAgent($userAgent);

    /**
     * Connection type preferred by the HTTP user agent.
     *
     * restrictions: optional, string with length <= 25
     * @return string
     */
    public function getConnection();

    /**
     * @param string
     * @return self
     */
    public function setConnection($connection);

    /**
     * Raw cookies found in the HTTP request.
     *
     * restrictions: optional, whitespace normalized string
     * @return string
     */
    public function getCookies();

    /**
     * @param string
     * @return self
     */
    public function setCookies($cookies);

    /**
     * User cookie placed in the browser.
     *
     * restrictions: optional, string with length <= 50
     * @return string
     */
    public function getUserCookie();

    /**
     * @param string
     * @return self
     */
    public function setUserCookie($userCookie);

    /**
     * OS of the user's machine specified by the user agent.
     *
     * restrictions: optional, string with length <= 100
     * @return string
     */
    public function getUserAgentOs();

    /**
     * @param string
     * @return self
     */
    public function setUserAgentOs($userAgentOs);

    /**
     * CPU of the user's machine specified by the user agent.
     *
     * restrictions: optional, string with length <= 100
     * @return string
     */
    public function getUserAgentCpu();

    /**
     * @param string
     * @return self
     */
    public function setUserAgentCpu($userAgentCpu);

    /**
     * Header from the user's communication to the webstore.
     *
     * restrictions: optional, string with length <= 100
     * @return string
     */
    public function getHeaderFrom();

    /**
     * @param string
     * @return self
     */
    public function setHeaderFrom($headerFrom);

    /**
     * Name of the user's browser.
     *
     * restrictions: optional, string with length <= 100
     * @return string
     */
    public function getWebBrowserName();

    /**
     * @param string
     * @return self
     */
    public function setWebBrowserName($webBrowserName);

    /**
     * Fraud protection data collected by JS.
     *
     * restrictions: whitespace normalized string
     * @return string
     */
    public function getJavascriptData();

    /**
     * @param string
     * @return self
     */
    public function setJavascriptData($javascriptData);

    /**
     * HTTP referrer URL.
     *
     * restrictions: string with length <= 1024
     * @return string
     */
    public function getReferrer();

    /**
     * @param string
     * @return self
     */
    public function setReferrer($referrer);

    /**
     * Client acceptable content types - Accept header.
     *
     * restrictions: string with length <= 1024
     * @return string
     */
    public function getContentTypes();

    /**
     * @param string
     * @return self
     */
    public function setContentTypes($contentTypes);

    /**
     * Client acceptable encodings - Accept-Encoding header.
     *
     * restrictions: string with length <= 50
     * @return string
     */
    public function getEncoding();

    /**
     * @param string
     * @return self
     */
    public function setEncoding($encoding);

    /**
     * Client acceptable (human) languages - Accept-Language header.
     *
     * restrictions: string with length <= 255
     * @return string
     */
    public function getLanguage();

    /**
     * @param string
     * @return self
     */
    public function setLanguage($language);

    /**
     * Client acceptable char sets - Accept-Charset
     *
     * restrictions: string with length <= 50
     * @return string
     */
    public function getCharSet();

    /**
     * @param string
     * @return self
     */
    public function setCharSet($charSet);
}
