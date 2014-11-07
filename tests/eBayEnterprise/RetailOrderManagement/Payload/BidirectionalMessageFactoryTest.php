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

class BidirectionalMessageFactoryTest extends \PHPUnit_Framework_TestCase
{
    const SUPPORTED_MESSAGE_TYPE = 'supported/message/type';
    const UNSUPPORTED_MESSAGE_TYPE = 'unsupported/message/type';

    /** @var \eBayEnterprise\RetailOrderManagement\Api\IConfig */
    protected $config;
    /** @var BidirectionalMessageFactory */
    protected $messageFactory;
    /** @var array */
    protected $messageTypes;
    /** @var IPayload */
    protected $payload;

    public function setUp()
    {
        // placeholder object, just so the factory will have something to return
        $this->payload = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayload');
        // this will be stubbed to return expected message type config keys
        $this->config = $this->getMock('\eBayEnterprise\RetailOrderManagement\Api\IConfig');
        // stub the payload factory to always just return a payload
        $this->payloadFactory = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadFactory');
        $this->payloadFactory->expects($this->any())
            ->method('buildPayload')
            ->will($this->returnValue($this->payload));
        $this->messageTypes = [
            self::SUPPORTED_MESSAGE_TYPE => [
                'request' => '\eBayEnterprise\RetailOrderManagement\Payload\IPayload',
                'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\IPayload',
            ],
        ];
        $this->messageFactory = new BidirectionalMessageFactory($this->config, $this->payloadFactory, $this->messageTypes);
    }
    /**
     * Provide message types - supported or unsupported
     * @return array
     */
    public function provideMessageType()
    {
        return [
            [self::SUPPORTED_MESSAGE_TYPE],
            [self::UNSUPPORTED_MESSAGE_TYPE],
        ];
    }
    /**
     * Test getting a request payload
     * @param  string $messageType
     * @dataProvider provideMessageType
     */
    public function testRequestPayload($messageType)
    {
        $this->config->expects($this->any())
            ->method('getConfigKey')
            ->will($this->returnValue($messageType));
        if ($messageType !== self::SUPPORTED_MESSAGE_TYPE) {
            $this->setExpectedException('eBayEnterprise\RetailOrderManagement\Payload\Exception\UnsupportedPayload');
        }
        $this->assertSame($this->payload, $this->messageFactory->requestPayload());
    }
    /**
     * Test getting a reply payload
     * @param  string $messageType
     * @dataProvider provideMessageType
     */
    public function testReplyPayload($messageType)
    {
        $this->config->expects($this->any())
            ->method('getConfigKey')
            ->will($this->returnValue($messageType));
        if ($messageType !== self::SUPPORTED_MESSAGE_TYPE) {
            $this->setExpectedException('eBayEnterprise\RetailOrderManagement\Payload\Exception\UnsupportedPayload');
        }
        $this->assertSame($this->payload, $this->messageFactory->replyPayload());
    }
}
