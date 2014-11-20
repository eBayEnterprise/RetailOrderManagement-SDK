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

use PhpAmqpLib\Message\AMQPMessage;

class AmqpPayloadIteratorTest extends \PHPUnit_Framework_TestCase
{
    const MAX_MESSAGES = 5;

    /** @var \eBayEnterprise\RetailOrderManagement\Api\IAmqpApi */
    protected $api;
    /** @var AmqpPayloadIterator */
    protected $iterator;
    protected $payload;
    /** @var IMessageFactory */
    protected $messageFactory;

    public function setUp()
    {
        // stub an API instance to return AMQP messages
        $this->api = $this->getMockBuilder('\eBayEnterprise\RetailOrderManagement\Api\IAmqpApi')
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        // stub an IOrderEvent payload to be returned from the message factory
        $this->payload = $this->getMockBuilder('\eBayEnterprise\RetailOrderManagement\Payload\IPayload')
            ->disableOriginalConstructor()
            ->setMethods(['deserialize'])
            ->getMockForAbstractClass();
        $this->payload->expects($this->any())
            ->method('deserialize')
            ->will($this->returnSelf());
        // stub a message factory to return the stub payloads when processing messages off the queue
        $this->messageFactory = $this->getMockBuilder('\eBayEnterprise\RetailOrderManagement\Payload\IMessageFactory')
            ->getMockForAbstractClass();
        $this->messageFactory->expects($this->any())
            ->method('messagePayload')
            ->will($this->returnValue($this->payload));
        // create the iterator to test and inject it with the stubs
        $this->iterator = new AmqpPayloadIterator($this->api, $this->messageFactory, self::MAX_MESSAGES);
    }
    /**
     * Test iterating over the payloads, processing only as many messages as
     * allowed by the max messages to process.
     */
    public function testIterateMaxMessagesCutoff()
    {
        $body = '<test xmlns="http://api.gsicommerce.com/schema/checkout/1.0" timestamp="2000-01-01T00:00:00-00:00"/>';
        $this->api->expects($this->any())
            ->method('getNextMessage')
            ->will($this->returnValue(new AMQPMessage($body, ['type' => 'Test'])));
        $processed = 0;
        while ($this->iterator->valid()) {
            $processed++;
            $this->iterator->next();
        }
        $this->assertSame(self::MAX_MESSAGES, $processed);
    }
    /**
     * Test getting messages until the queue runs out. When no messages left in the queue,
     * getNextMessage will return null, indicating no additional messages.
     * Iterable should end when no additional messages left in the queue.
     */
    public function testIterateExhaustMessages()
    {
        $body = '<test xmlns="http://api.gsicommerce.com/schema/checkout/1.0" timestamp="2000-01-01T00:00:00-00:00"/>';
        $this->api->expects($this->any())
            ->method('getNextMessage')
            // return a new message twice, then null
            ->will($this->onConsecutiveCalls(
                new AMQPMessage($body, ['type' => 'Test']),
                new AMQPMessage($body, ['type' => 'Test'])
            ));

        $processed = 0;
        while ($this->iterator->valid()) {
            $processed++;
            $this->iterator->next();
        }
        $this->assertSame(2, $processed);
    }
}
