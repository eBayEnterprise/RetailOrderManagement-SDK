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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Common logger utility methods.
 *
 * trait TLogger
 * @package eBayEnterprise\RetailOrderManagement\Api
 */
trait TLogger
{
    /**
     * Check if the logger has a method name 'getContext'.
     * @return bool
     */
    protected function hasContext()
    {
        return method_exists($this->logger, 'getContext');
    }

    /**
     * Get an instance of the context class or null if not a valid logger.
     * @return mixed | null
     */
    protected function getContext()
    {
        return $this->hasContext() ? $this->logger->getContext() : null;
    }

    /**
     * Add the request/response payload body to the log context.
     *
     * @param  string
     * @param  string
     * @return array
     */
    protected function addLogContext($key, $xmlPayload)
    {
        $context = $this->getContext();
        $logData = [
            'app_context' => 'http',
            'rom_request_url' => $this->config->getEndpoint(),
            $key => $xmlPayload,
        ];
        return $context ? $context->getMetaData(__CLASS__, $logData) : $logData;
    }

    /**
     * Get the log data for the request URL.
     * @return array
     */
    abstract protected function getRequestUrlLogData();

    /**
     * Logging the API endpoint if we have a valid logger.
     * @return self
     */
    abstract protected function logRequestUrl();
}
