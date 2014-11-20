<?php

namespace eBayEnterprise\RetailOrderManagement\Api;

use eBayEnterprise\RetailOrderManagement\Util\TTestReflection;

class AmqpApiTest extends \PHPUnit_Framework_TestCase
{
    use TTestReflection;

    /** @var IAmqpConfig (stub) */
    public $config;
    /** @var \PhpAmqpLib\Connection\AMQPConnection */
    public $connection;
    /** @var \PhpAmqpLib\Channel\AMQPChannel */
    public $channel;
    /** @var AmqpApi */
    public $amqpApi;

    /**
     * Set up dependencies and test objects
     */
    public function setUp()
    {
        $this->config = $this->getMockBuilder('\eBayEnterprise\RetailOrderManagement\Api\IAmqpConfig')
            ->getMockForAbstractClass();

        $this->connection = $this->getMockBuilder('\PhpAmqpLib\Connection\AMQPConnection')
            // Prevent constructor from opening sockets, checking PECL packages, and constructing
            // AMQP reader and writer objects, all irrelevant to these tests. Also avoids the
            // need to inject connection configuration to create the mock.
            ->disableOriginalConstructor()
            ->getMock();
        $this->channel = $this->getMockBuilder('\PhpAmqpLib\Channel\AMQPChannel')
            // Prevent opening of actual AMQP channels - which would also require
            // an open connection - and avoid having to inject channel configuration.
            ->disableOriginalConstructor()
            ->getMock();

        $this->amqpApi = new AmqpApi($this->config, ['connection' => $this->connection, 'channel' => $this->channel]);
    }
    /**
     * Mock out a set of configuration get methods
     * @param array $configMethods Key/value pairs of config get method and scripted return value
     * @return self
     */
    protected function setStubConfigData($configMethods = [])
    {
        foreach ($configMethods as $method => $configValue) {
            $this->config->expects($this->any())
                ->method($method)
                ->will($this->returnValue($configValue));
        }
        return $this;
    }
    /**
     * Test creating the AMQP connection
     */
    public function testConnect()
    {
        $connectionType = 'Connection\Class\Name';
        $connectionParams = ['example.com', '5672', 'example', 'secret', '/', 'more'];

        // mock out configuration
        $this->setStubConfigData([
            'getConnectionType' => $connectionType,
            'getConnectionConfiguration' => $connectionParams,
        ]);

        // @var \ReflectionClass (mock)
        $reflector = $this->getMockBuilder('ReflectionClass')
            // Allow reflection class mock to be constructed without having to
            // feed it a real class/object.
            ->disableOriginalConstructor()
            ->setMethods(['newInstanceArgs'])
            ->getMock();
        $reflector->expects($this->once())
            ->method('newInstanceArgs')
            ->with($this->equalTo($connectionParams))
            ->will($this->returnValue(null));

        $amqpApi = $this->getMockBuilder('\eBayEnterprise\RetailOrderManagement\Api\AmqpApi')
            ->setMethods(['getConnectionReflectionClass'])
            ->setConstructorArgs([$this->config])
            ->getMock();

        $amqpApi->expects($this->any())
            ->method('getConnectionReflectionClass')
            ->will($this->returnValue($reflector));

        $this->invokeRestrictedMethod($amqpApi, 'connect');
    }
    /**
     * Test closing the connection - should close the connection as well as the
     * channel
     */
    public function testCloseConnection()
    {
        $this->connection->expects($this->once())
            ->method('close')
            ->will($this->returnValue(true));
        $this->channel->expects($this->once())
            ->method('close')
            ->will($this->returnValue(true));

        $this->amqpApi->closeConnection();
    }
    /**
     * Test declaring a queue on the channel using the configured queue name
     */
    public function testQueueDeclaration()
    {
        $qName = 'queue_name';
        $this->setStubConfigData([
            'getQueueName' => $qName,
            'getQueueConfiguration' => [
                'queue' => $qName,
                'passive' => false,
                'durable' => true,
                'exclusive' => true,
                'auto_delete' => true,
                'nowait' => true,
            ],
        ]);
        $this->channel->expects($this->once())
            ->method('queue_declare')
            ->with($this->identicalTo($qName));
        $this->invokeRestrictedMethod($this->amqpApi, 'declareQueue');
    }
    /**
     * Script the config stub to return data needed for testing queue binding
     * @param  string $queueName
     * @param  string $exchangeName
     * @param  array $routes
     * @return self
     */
    protected function stubConfigForQueueBinding($queueName, $exchangeName, $routes)
    {
        $this->setStubConfigData([
            'getQueueName' => $queueName, 'getConnectionRouteKeys' => $routes, 'getExchangeName' => $exchangeName
        ]);
        return $this;
    }
    public function testOpenConnection()
    {
        /** @var \eBayEnterprise\RetailOrderManagement\Api\AmqpApi $amqpApi */
        $amqpApi = $this->getMock(
            '\eBayEnterprise\RetailOrderManagement\Api\AmqpApi',
            ['connect', 'createChannel', 'declareQueue', 'isConnected'],
            [$this->config]
        );
        // mock methods to create connections/queues/exchanges to expect
        // to only be called once
        $amqpApi->expects($this->once())
            ->method('connect')
            ->will($this->returnSelf());
        $amqpApi->expects($this->once())
            ->method('createChannel')
            ->will($this->returnSelf());
        $amqpApi->expects($this->once())
            ->method('declareQueue')
            ->will($this->returnSelf());
        $amqpApi->expects($this->any())
            ->method('isConnected')
            ->will($this->returnValue(true));
        // happy path should just return self
        $this->assertSame($amqpApi, $amqpApi->openConnection());
        // should be able to call it multiple times without actually invoking
        // any of the methods to create connections, queues, exchanges, etc.
        $this->assertSame($amqpApi, $amqpApi->openConnection());
    }
    public function testOpenConnectionFailure()
    {
        /** @var \eBayEnterprise\RetailOrderManagement\Api\AmqpApi $amqpApi */
        $amqpApi = $this->getMock(
            '\eBayEnterprise\RetailOrderManagement\Api\AmqpApi',
            ['connect', 'createChannel', 'declareQueue', 'isConnected'],
            [$this->config]
        );

        /** @var \PhpAmqpLib\Exception\AMQPProtocolException $protocolException */
        $protocolException = $this->getMockBuilder('\PhpAmqpLib\Exception\AMQPProtocolException')
            // Avoid needing to provide valid reply_code, reply_text or method_sig. This exception
            // type comes from PhpAmqpLib internals so it will always know how to construct
            // these properly.
            ->disableOriginalConstructor()
            ->getMock();
        // any AMQP exceptions thrown while setting up the queue should be caught
        $amqpApi->expects($this->any())
            ->method('connect')
            ->will($this->throwException($protocolException));
        $amqpApi->expects($this->any())
            ->method('createChannel')
            ->will($this->returnSelf());
        $amqpApi->expects($this->any())
            ->method('declareQueue')
            ->will($this->returnSelf());
        // simulate the connection failing to be created
        $amqpApi->expects($this->any())
            ->method('isConnected')
            ->will($this->returnValue(false));

        // when the connection fails to be created, should result in a
        // ConnectionError being thrown
        $this->setExpectedException('\eBayEnterprise\RetailOrderManagement\Api\Exception\ConnectionError');

        $amqpApi->openConnection();
    }
}
