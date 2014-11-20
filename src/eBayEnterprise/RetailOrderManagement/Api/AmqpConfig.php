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
 * Class AmqpConfig
 * @package eBayEnterprise\RetailOrderManagement\Api
 */
class AmqpConfig implements IAmqpConfig
{
    protected $connectionConfigTypes = [
        '\PhpAmqpLib\Connection\AMQPSSLConnection' =>
            '\eBayEnterprise\RetailOrderManagement\Api\Amqp\SslConnectionConfig',
        '\PhpAmqpLib\Connection\AMQPConnection' => '\eBayEnterprise\RetailOrderManagement\Api\Amqp\ConnectionConfig',
    ];
    /**
     * type of connection to make
     * @var string
     */
    protected $connectionType;
    /**
     * connection max messages to process configuration
     * @var int
     */
    protected $maxMessagesToProcess;
    /**
     * connection hostname configuration
     * @var string
     */
    protected $connectionHostname;
    /**
     * connection port configuration
     * @var string
     */
    protected $connectionPort;
    /**
     * connection username configuration
     * @var string
     */
    protected $connectionUsername;
    /**
     * connection password configuration
     * @var string
     */
    protected $connectionPassword;
    /**
     * connection vhost configuration
     * @var string
     */
    protected $connectionVhost;
    /**
     * connection connection context configuration
     * @var array
     */
    protected $connectionContext;
    /**
     * connection connection insist configuration
     * @var bool
     */
    protected $connectionInsist;
    /**
     * connection connection login method configuration
     * @var string
     */
    protected $connectionLoginMethod;
    /**
     * connection connection locale configuration
     * @var string
     */
    protected $connectionLocale;
    /**
     * connection connection timeout configuration
     * @var int
     */
    protected $connectionTimeout;
    /**
     * connection connection read write timeout configuration
     * @var int
     */
    protected $connectionReadWriteTimeout;
    /**
     * connection queue name
     * @var string
     */
    protected $queueName;
    /**
     * connection queue passive configuration
     * @var bool
     */
    protected $queuePassive;
    /**
     * connection queue durable configuration
     * @var bool
     */
    protected $queueDurable;
    /**
     * connection queue exclusive configuration
     * @var bool
     */
    protected $queueExclusive;
    /**
     * connection queue auto delete configuration
     * @var bool
     */
    protected $queueAutoDelete;
    /**
     * connection queue nowait configuration
     * @var bool
     */
    protected $queueNowait;

    /** @var Amqp\IConnectionConfig */
    protected $connectionConfig;

    /**
     * @param string $connectionType
     * @param int $maxMessagesToProcess
     * @param string $connectionHostname
     * @param string $connectionPort
     * @param string $connectionUsername
     * @param string $connectionPassword
     * @param string $connectionVhost
     * @param array $connectionContext
     * @param bool $connectionInsist
     * @param string $connectionLoginMethod
     * @param string $connectionLocale
     * @param int $connectionTimeout
     * @param int $connectionReadWriteTimeout
     * @param string $queueName
     * @param bool $queuePassive
     * @param bool $queueDurable
     * @param bool $queueExclusive
     * @param bool $queueAutoDelete
     * @param bool $queueNowait
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        $connectionType,
        $maxMessagesToProcess,
        $connectionHostname,
        $connectionPort,
        $connectionUsername,
        $connectionPassword,
        $connectionVhost,
        array $connectionContext,
        $connectionInsist,
        $connectionLoginMethod,
        $connectionLocale,
        $connectionTimeout,
        $connectionReadWriteTimeout,
        $queueName,
        $queuePassive,
        $queueDurable,
        $queueExclusive,
        $queueAutoDelete,
        $queueNowait
    ) {
        $this->connectionType = $connectionType;
        $this->maxMessagesToProcess = $maxMessagesToProcess;

        $this->connectionConfig = new $this->connectionConfigTypes[$this->connectionType](
            $connectionHostname,
            $connectionPort,
            $connectionUsername,
            $connectionPassword,
            $connectionVhost,
            $connectionContext,
            $connectionInsist,
            $connectionLoginMethod,
            $connectionLocale,
            $connectionTimeout,
            $connectionReadWriteTimeout
        );

        $this->queueName = $queueName;
        $this->queuePassive = $queuePassive;
        $this->queueDurable = $queueDurable;
        $this->queueExclusive = $queueExclusive;
        $this->queueAutoDelete = $queueAutoDelete;
        $this->queueNowait = $queueNowait;
    }
    public function getConnectionType()
    {
        return $this->connectionType;
    }
    public function getConnectionConfiguration()
    {
        return $this->connectionConfig->getConnectionParams();
    }
    public function getQueueConfiguration()
    {
        // keys unused but included for context
        return [
            'queue' => $this->queueName,
            'passive' => $this->queuePassive,
            'durable' => $this->queueDurable,
            'exclusive' => $this->queueExclusive,
            'auto_delete' => $this->queueAutoDelete,
            'nowait' => $this->queueNowait,
        ];
    }
    public function getQueueName()
    {
        return $this->queueName;
    }
    public function getMaxMessagesToProcess()
    {
        return $this->maxMessagesToProcess;
    }
}
