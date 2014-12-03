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
 * Interface IAmqpApi
 * @package eBayEnterprise\RetailOrderManagement\Api
 */
interface IAmqpApi extends IOmnidirectionalApi
{
    /**
     * Get the next message from the queue.
     * @return \PHPAmqpLib\Message\AMQPMessage
     * @throws Exception\ConnectionError If connection to queue cannot be established
     */
    public function getNextMessage();

    /**
     * Test for the AMQP client to be connected to the server.
     * @return boolean
     */
    public function isConnected();

    /**
     * Connect to the AMQP server and queue
     * @return self
     * @throws Exception\ConnectionError If the connection cannot be established
     */
    public function openConnection();

    /**
     * Close an open connection to the AMQP server and queue. This is done
     * automatically when the API is GC'd but can be called manually as well.
     * @return self
     */
    public function closeConnection();
}
