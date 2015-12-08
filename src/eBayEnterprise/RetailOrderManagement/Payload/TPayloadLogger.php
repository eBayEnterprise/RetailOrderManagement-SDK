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

namespace eBayEnterprise\RetailOrderManagement\Payload;

/**
 * Generic implementation strategies for things logger have to do.
 *
 * trait TPayloadLogger
 * @package eBayEnterprise\RetailOrderManagement\Payload
 */
trait TPayloadLogger
{
    /**
     * Check if the logger has a method name 'getContext'.
     * @return bool
     */
    protected function hasLogContext()
    {
        return method_exists($this->logger, 'getContext');
    }

    /**
     * Get an instance of the context class or null if not a valid logger.
     * @return mixed | null
     */
    protected function getLogContext()
    {
        return $this->hasLogContext() ? $this->logger->getContext() : null;
    }

    /**
     * Get context data from the logger if the logger has one.
     *
     * @param  string
     * @param  array
     * @return array
     */
    protected function getLogContextData($class, array $logData=[])
    {
        $context = $this->getLogContext();
        return $context ? $context->getMetaData($class, $logData) : $logData;
    }
}
