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

namespace eBayEnterprise\RetailOrderManagement\Api\Amqp;

/**
 * Class SslConnectionConfig
 * @package eBayEnterprise\RetailOrderManagement\Api\Amqp
 */
class SslConnectionConfig extends AbstractConnectionConfig
{
    /**
     * Get the constructor args used to configure an AMQP connection.
     * Array may be keyed by constructor param, although this is mainly
     * just for documentation purposes. All that really matters is the
     * order of the array match the order of the constructor arguments.
     * @return array
     */
    public function getConnectionParams()
    {
        return [
            'host' => $this->connectionHostname,
            'port' => $this->connectionPort,
            'user' => $this->connectionUsername,
            'password' => $this->connectionPassword,
            'vhost' => $this->connectionVhost,
            'ssl_options' => $this->connectionContext,
            // any "false" values should fall back to defaults - which
            // will either be a false value, e.g. 'insist', or will fall
            // back to an appropriate default value when a false
            // value wouldn't apply, e.g. 'locale' or 'login_method'
            'options' => array_filter([
                'insist' => $this->connectionInsist,
                'login_method' => $this->connectionLoginMethod,
                'locale' => $this->connectionLocale,
                'connection_timeout' => $this->connectionTimeout,
                'read_write_timeout' => $this->connectionReadWriteTimeout,
            ]),
        ];
    }
}
