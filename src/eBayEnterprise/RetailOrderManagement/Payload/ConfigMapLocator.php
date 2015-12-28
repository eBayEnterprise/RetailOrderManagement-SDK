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
 * Payload locator using a default mapping found in PayloadConfigMap.php.
 */
class ConfigMapLocator extends AbstractConfigLocator
{
    /**
     * Array of configuration data used to describe how to construct various
     * types of payloads. Stored as a static property so each instance doesn't
     * need it's own copy of the configuration, which has grown quite large.
     *
     * @var array
     */
    protected static $config;

    /**
     * @param array $config Payload locator configuration
     */
    public function __construct()
    {
        if (!self::$config) {
            self::$config = require 'PayloadConfigMap.php';
        }
    }

    /**
     * Get the blob of payload config data.
     *
     * @return array
     */
    protected function getConfig()
    {
        return self::$config;
    }
}
