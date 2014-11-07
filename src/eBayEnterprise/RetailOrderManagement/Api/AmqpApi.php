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

use eBayEnterprise\RetailOrderManagement\Api\Exception\ConnectionError;
use eBayEnterprise\RetailOrderManagement\Payload\AmqpPayloadIterator;
use eBayEnterprise\RetailOrderManagement\Payload;
use PhpAmqpLib\Channel\AbstractChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Exception\AMQPExceptionInterface;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class AmqpApi
 * @package eBayEnterprise\RetailOrderManagement\Api
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AmqpApi implements IAmqpApi
{
    /** @var IAmqpConfig */
    protected $config;
    /** @var AbstractConnection */
    protected $connection;
    /** @var AbstractChannel */
    protected $channel;
    /** @var bool Flag for if the queue has been connected to and is ready to start receiving messages */
    protected $isQueueSetup = false;
    /** @var Payload\OmnidirectionalMessageFactory */
    protected $messageFactory;
    /**
     * Inject config and allow a connection and channel to be injected.
     * @param IAmpqConfig $config
     * @param mixed[]      $args   May contain:
     *                             - 'connection' => AbstractConnection
     *                             - 'channel' => AbstractChannel
     */
    public function __construct(IAmqpConfig $config, array $args = [])
    {
        $this->config = $config;
        // injectable primarially for testing purposes
        list($this->connection, $this->channel) = $this->checkTypes(
            isset($args['connection']) ? $args['connection'] : null,
            isset($args['channel']) ? $args['channel'] : null
        );
        $this->messageFactory = new Payload\OmnidirectionalMessageFactory();
    }
    /**
     * Check types of dependencies injected via the constructors $args array
     * @param AbstractConnection $connection
     * @param AbstractChannel    $channel
     * @return mixed
     */
    protected function checkTypes(AbstractConnection $connection = null, AbstractChannel $channel = null)
    {
        return [$connection, $channel];
    }
    /**
     * Connect to the AMQP server and create/declare the channel, exchange and queue.
     * Returns a new payload iterator which will consume consume messages from the queue
     * @return IPayloadIterator
     * @throws ConnectionError Thrown by self::openConnection if connection cannot be established
     */
    public function fetch()
    {
        $this->openConnection();
        return new AmqpPayloadIterator($this, $this->messageFactory, $this->config->getMaxMessagesToProcess());
    }
    /**
     * Get the next message from the queue.
     * @return AMQPMessage
     * @throws ConnectionError Thrown by self::openConnection if connection cannot be established
     */
    public function getNextMessage()
    {
        $this->openConnection();
        $message = $this->channel->basic_get($this->config->getQueueName());
        // Currently no reason to want to see any message more than once so
        // ack every message received from the queue.
        if ($message) {
            // direct delivery_info array access recommended in PhpAmqpLib documentation
            $this->channel->basic_ack($message->delivery_info['delivery_tag']);
        }
        return $message;
    }
    public function closeConnection()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        if ($this->connection) {
            $this->connection->close();
        }
        $this->isQueueSetup = false;
        return $this;
    }
    public function isConnected()
    {
        return $this->connection && $this->connection->isConnected();
    }
    /**
     * Connect to the queue. Any method called should be idempotent so this method may be
     * called multiple times without creating additional connections, exchanges, queues, etc.
     * @return self
     * @throws ConnectionError If the queue cannot be setup successfully
     */
    public function openConnection()
    {
        // actions are all idempotent but checking the flag first helps prevents some wasted cycles
        if (!$this->isQueueSetup) {
            // default error message if the connection fails
            $errorMessage = 'Unable to connect to the AMQP server.';
            try {
                $this->connect()->createChannel()->declareQueue();
            } catch (AMQPExceptionInterface $e) {
                // catch any exceptions while attempting to create the exception
                // and capture a likely more specific error message
                $errorMessage = $e->getMessage();
            }
            // if the connection failed, throw an exception indicating the
            // AMQP connection could not be established
            if (!$this->isConnected()) {
                throw new ConnectionError($errorMessage);
            }
            $this->isQueueSetup = true;
        }
        return $this;
    }
    /**
     * Get a reflection class instance for the type of connection to use,
     * separated out to keep the dependency on ReflectionClass secluded.
     * @param string $connectionClass
     * @return ReflectionClass
     */
    protected function getConnectionReflectionClass($connectionClass)
    {
        return new \ReflectionClass($connectionClass);
    }
    /**
     * Create the connection - uses a ReflectionClass to create the instance to
     * allow for more flexibility in the constructor args used - needs to be able
     * to pass a different number of arguments depending on the connection
     * configuration.
     * @return self
     */
    protected function connect()
    {
        if ($this->connection === null) {
            $this->connection = $this->getConnectionReflectionClass($this->config->getConnectionType())
                ->newInstanceArgs($this->config->getConnectionConfiguration());
        }
        return $this;
    }
    /**
     * Create a channel from the connection if one does not already exist.
     * Requires the connection to already have been created.
     * @return self
     */
    protected function createChannel()
    {
        if ($this->channel === null) {
            $this->channel = $this->connection->channel();
        }
        return $this;
    }
    /**
     * Declare a queue on the channel. Declaring a queue that already exists will
     * simply verify that the queue exists.
     * @return self
     */
    protected function declareQueue()
    {
        call_user_func_array(
            [$this->channel, 'queue_declare'],
            $this->config->getQueueConfiguration()
        );
        return $this;
    }
}
