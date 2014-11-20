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
 * Interface IHttpConfig
 * @package eBayEnterprise\RetailOrderManagement\Api
 *
 * Provides an Http Api object
 * with the metadata it needs to send and/or receive
 * the right payload objects to the right place
 */
interface IHttpConfig extends IConfig
{
    /**
     * Service URI has the following format:
     * https://{host}/v{M}.{m}/stores/{storeid}/{service}/{operation}{/parameters}.{format}
     * - host - EE Exchange Platform domain
     * - M - major version of the API
     * - m - minor version of the API
     * - storeid - GSI assigned store identifier
     * - service - API call service/subject area
     * - operation - specific API call of the specified service
     * - format - extension of the requested response format. Currently only xml is supported
     */
    const URI_FORMAT = 'https://%s/v%s.%s/stores/%s/%s/%s%s.xml';

    /**
     * Key used to authenticate the API
     *
     * @return string
     */
    public function getApiKey();

    /**
     * URI for service operation
     *
     * @return string
     */
    public function getEndpoint();

    /**
     * Get the http method to use for communicating with ROM via Http
     *
     * @return string
     */
    public function getHttpMethod();

    /**
     * Get the content type for communicating with ROM. E.g. as the content-type header.
     *
     * @return string
     */
    public function getContentType();
}
