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
 * Interface IAmqpConfig
 * @package eBayEnterprise\RetailOrderManagement\Api
 *
 * Provides an AMQP Api object
 * with the metadata it needs to fetch
 * the right payload objects to the right place
 */
interface IAmqpConfig
{
    /**
     * type of AMQP connection to make
     * @return string
     */
    public function getConnectionType();

    /**
     * get connection max messages to process configuration
     * @return int
     */
    public function getMaxMessagesToProcess();

    /**
     * get connection queue name configuration
     * @return string
     */
    public function getQueueName();

    /**
     * get an array of arguments to be given when creating the AMQP connection
     * array must be in order matching the constructor arguments to be given
     * when creating the connection instance
     * @return array
     */
    public function getConnectionConfiguration();

    /**
     * get an array of arguments to be given when creating the AMQP queue
     * array must be in order matching the method params when creating
     * the queue
     * @return array
     */
    public function getQueueConfiguration();

    /**
     * Constructing the AMQP full uri.
     * @return string
     */
    public function getEndpoint();
}
