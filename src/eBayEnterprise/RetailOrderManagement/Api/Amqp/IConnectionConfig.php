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
 * Interface IConnectionConfig
 * @package eBayEnterprise\RetailOrderManagement\Api\Amqp
 *
 * Provides the AmqpConfig object with params needed specifically for the type
 * of AMQP connection being created.
 */
interface IConnectionConfig
{
    /**
     * Get the constructor args used to configure an AMQP connection.
     * Array may be keyed by constructor param, although this is mainly
     * just for documentation purposes. All that really matters it the
     * order of the array match the order of the constructor arguments.
     * @return array
     */
    public function getConnectionParams();
}
