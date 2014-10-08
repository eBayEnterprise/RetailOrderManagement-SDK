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


namespace eBayEnterprise\RetailOrderManagement\Api;

/**
 * Class HttpConfig
 * @package eBayEnterprise\RetailOrderManagement\Api
 */
class HttpConfig implements iConfig
{
    /**
     * Service URI has the following format:
     * https://{host}/v{M}.{m}/stores/{storeid}/{service}/{operation}{/parameters}.{format}
     * - host - EE Excnahge Platform domain
     * - M - major version of the API
     * - m - minor version of the API
     * - storeid - GSI assigned store identifier
     * - service - API call service/subject area
     * - operation - specific API call of the specified service
     * - format - extension of the requested response format. Currently only xml is supported
     */
    const URI_FORMAT = 'https://%s/v%s.%s/stores/%s/%s/%s.xml';

    protected $apiKey;
    protected $host;
    protected $majorVersion;
    protected $minorVersion;
    protected $storeId;
    protected $service;
    protected $operation;
    protected $action;
    protected $contentType;

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getEndpoint()
    {
        return sprintf(
            self::URI_FORMAT,
            $this->host,
            $this->majorVersion,
            $this->minorVersion,
            $this->storeId,
            $this->service,
            $this->operation
        );
    }

    public function getServiceOperation()
    {
        return array($this->service, $this->operation);
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param $apiKey
     * @param $host
     * @param $majorVersion
     * @param $minorVersion
     * @param $storeId
     * @param $service
     * @param $operation
     */
    public function __construct($apiKey, $host, $majorVersion, $minorVersion, $storeId, $service, $operation)
    {
        $this->apiKey = $apiKey;
        $this->host = $host;
        $this->majorVersion = $majorVersion;
        $this->minorVersion = $minorVersion;
        $this->storeId = $storeId;
        $this->service = $service;
        $this->operation = $operation;
		$this->action = 'post';
		$this->contentType = 'text/xml';
    }
}
