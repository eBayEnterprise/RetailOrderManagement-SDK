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
class HttpConfig implements IHttpConfig
{
    protected $apiKey;
    protected $host;
    protected $majorVersion;
    protected $minorVersion;
    protected $storeId;
    protected $service;
    protected $operation;
    protected $endpointParams;
    protected $action = 'post';
    protected $contentType = 'text/xml';

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
            $this->operation,
            ($this->endpointParams ? '/' . implode('/', $this->endpointParams) : '')
        );
    }

    public function getConfigKey()
    {
        return $this->service . '/' . $this->operation;
    }

    public function getHttpMethod()
    {
        return $this->action;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $apiKey
     * @param string $host
     * @param string $majorVersion
     * @param string $minorVersion
     * @param string $storeId
     * @param string $service
     * @param string $operation
     * @param array  $endpointParams If additional params are provided, they will be joined on '/' and appended
     *                               with a '/' to the operation at the end of the endpoint URI.
     */
    public function __construct(
        $apiKey,
        $host,
        $majorVersion,
        $minorVersion,
        $storeId,
        $service,
        $operation,
        array $endpointParams = []
    ) {
        $this->apiKey = $apiKey;
        $this->host = $host;
        $this->majorVersion = $majorVersion;
        $this->minorVersion = $minorVersion;
        $this->storeId = $storeId;
        $this->service = $service;
        $this->operation = $operation;
        $this->endpointParams = $endpointParams;
    }
}
