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

use eBayEnterprise\RetailOrderManagement\Payload;
use eBayEnterprise\RetailOrderManagement\Util\TTestReflection;
use eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedOperation;
use Requests_Exception_HTTP;

class HttpApiTest extends \PHPUnit_Framework_TestCase
{
    use TTestReflection;

    protected $requestPayloadStub;
    protected $replyPayloadStub;
    /** @var HttpApi */
    protected $api;
    /** @var HttpConfig */
    protected $configStub;
    /** @var HttpApi */
    protected $apiStub;
    protected $requestsResponseStub;

    public function setUp()
    {
        // create payload mocks
        $this->requestPayloadStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IPayload');
        $this->replyPayloadStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IPayload');

        // create mocks for the config and api objects
        $this->configStub = $this->getMock(
            'eBayEnterprise\RetailOrderManagement\Api\HttpConfig',
            null,
            ['key', 'host', 'major', 'minor', 'store', 'service', 'operation']
        );
        $this->apiStub = $this->getMock(
            'eBayEnterprise\RetailOrderManagement\Api\HttpApi',
            ['sendRequest'],
            [$this->configStub]
        );

        // create a mock of the Requests_Response object
        $this->requestsResponseStub = $this->getMock('Requests_Response');

        // create an actual API object using the Config mock
        $this->api = new HttpApi($this->configStub);
        $this->injectPayloads($this->api, $this->requestPayloadStub, $this->replyPayloadStub);

        // inject the Config mock into our API mock
        $this->injectPayloads($this->apiStub, $this->requestPayloadStub, $this->replyPayloadStub);

    }

    protected function injectPayloads($class, $requestPayload, $replyPayload)
    {
        // use reflection to inject payloads into the HttpApi object
        $this->setRestrictedPropertyValues(
            $class,
            [
                'requestPayload' => $requestPayload,
                'replyPayload' => $replyPayload
            ]
        );
    }

    public function provideSendRequestAction()
    {
        return [
            [true, 'post'],
            [true, 'get'],
            [false, 'options'],
            [false, 'head'],
            [false, 'put'],
            [false, 'delete'],
            [false, 'trace'],
            [false, 'connect'],
            [false, 'patch'],
        ];
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSendInvalidRequestPayload()
    {
        $this->requestPayloadStub->expects($this->any())
            ->method('serialize')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $this->api->send();
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSendReceiveInvalidReplyPayload()
    {
        $this->requestsResponseStub->body = '';
        $this->setRestrictedPropertyValues($this->apiStub, ['lastRequestsResponse' => $this->requestsResponseStub]);

        $this->apiStub->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue(true));
        $this->replyPayloadStub->expects($this->any())
            ->method('deserialize')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $this->apiStub->send();
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Api\Exception\NetworkError
     */
    public function testSendFailsSendRequest()
    {
        $this->requestsResponseStub->body = '';
        $this->requestsResponseStub->success = false;
        $this->requestsResponseStub->status_code = 404;
        $this->setRestrictedPropertyValues($this->apiStub, ['lastRequestsResponse' => $this->requestsResponseStub]);

        $this->requestsResponseStub->url = 'http://no.such.url';
        $this->apiStub->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue(false));
        $this->apiStub->send();
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Api\Exception\NetworkError
     */
    public function testSendHandlesRequestsException()
    {
        $this->apiStub->expects($this->any())
            ->method('sendRequest')
            ->will($this->throwException(new Requests_Exception_HTTP));
        $this->apiStub->send();
    }

    /**
     * @param bool $supported if false, expect error
     * @param $action
     * @dataProvider provideSendRequestAction
     */
    public function testSendRequestHandlesAction($supported, $action)
    {
        $this->setRestrictedPropertyValues($this->configStub, ['action' => $action]);
        $stub = $this->getMock(
            'eBayEnterprise\RetailOrderManagement\Api\HttpApi',
            ['post', 'get'],
            [$this->configStub]
        );

        $stub->expects($this->any())
            ->method($action)
            ->will($this->returnValue(true));

        if (!$supported) {
            $this->setExpectedException('\eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedHttpAction');
        }
        $actual = $this->invokeRestrictedMethod($stub, 'sendRequest');

        $this->assertTrue($actual);
    }

    /**
     * Test that the method HttpApi::logPayloadMessage() once invoked, will
     * be passed in as first parameter a string of xml payload, as second parameter
     * a string value literal, and a second parameter another string value literal.
     * Then, the following methods will be invoked: LoggerInterface::debug(),
     * and HttpApi::addLogContext(). The method LoggerInterface::debug() will be passed in
     * as first parameter a string message value, and as second parameter
     * the return array from calling the method HttpApi::addLogContext(). The method
     * HttpApi::addLogContext() will be passed in as first parameter the string value literal
     * and the passed in string of xml payload as second parameter. Finally, the method
     * HttpApi::logPayloadMessage() will return itself.
     */
    public function testLogPayloadMessage()
    {
        $requestData = '<request/>';
        /** @var string */
        $logMessage = 'Payload request body';
        /** @var string */
        $key = 'rom_request_body';
        /** @var null */
        $void = null;
        /** @var array */
        $context = [
            'app_context' => 'http',
            'rom_request_url' => 'https://test-api.example.com/inventory/request.xml',
            $key => $requestData,
        ];
        /** @var LoggerInterface */
        $logger = $this->getMock('Psr\Log\NullLogger', ['debug']);
        $logger->expects($this->once())
            ->method('debug')
            ->with($this->identicalTo($logMessage), $this->identicalTo($context))
            ->will($this->returnValue($void));

        /** @var HttpApi */
        $api = $this->getMockBuilder('eBayEnterprise\RetailOrderManagement\Api\HttpApi')
            ->setMethods(['addLogContext'])
            // Disabling the constructor because it is not necessary for this test.
            ->disableOriginalConstructor()
            ->getMock();
        $api->expects($this->once())
            ->method('addLogContext')
            ->with($this->identicalTo($key), $this->identicalTo($requestData))
            ->will($this->returnValue($context));

        $this->setRestrictedPropertyValues($api, ['logger' => $logger]);
        $this->assertSame($api, $this->invokeRestrictedMethod($api, 'logPayloadMessage', [$requestData, $logMessage, $key]));
    }

    /**
     * Test that the method HttpApi::addLogContext() once invoked, will
     * be passed in as first parameter a string literal, and as second parameter
     * an IPayload object. Then, the method HttpApi::getContext() will be invoked
     * and return an object that must have a method call getMetadata(). For the purpose
     * of this test, the stdClass class is being mocked in order to show the behavior of this
     * method. Then, the method HttpConfig::getEndpoint() will be invoked and the return value
     * will be map an array with key 'rom_request_url', then the passed in key will be
     * another key in the array, mapped to the passed in xml payload parameter.
     * Then, if the method HttpApi::getContext() return
     * a valid object, the method getMetadata() is invoked on that object passing in as
     * first parameter a string value of the class name, and as second parameter
     * an array with key/value pairs is passed in.Finally, the HttpApi::addLogContext()
     * will return this array of key/value pairs.
     */
    public function testAddLogContext()
    {
        $apiClass = 'eBayEnterprise\RetailOrderManagement\Api\HttpApi';
        $key = 'rom_request_body';
        $endPoint = 'https://test-api.example.com/inventory/request.xml';
        $xml = '<request/>';
        /** @var array */
        $context = [
            'app_context' => 'http',
            'rom_request_url' => $endPoint,
            $key => $xml,
        ];

        // Faking a context class that must have the method getMetaData()
        $concreteContext = $this->getMock('stdClass', ['getMetaData']);
        $concreteContext->expects($this->once())
            ->method('getMetaData')
            ->with($this->identicalTo($apiClass), $this->identicalTo($context))
            ->will($this->returnValue($context));

        $config = $this->getMock(
            'eBayEnterprise\RetailOrderManagement\Api\HttpConfig',
            ['getEndpoint'],
            ['key', 'host', 'major', 'minor', 'store', 'service', 'operation']
        );
        $config->expects($this->once())
            ->method('getEndpoint')
            ->will($this->returnValue($endPoint));

        /** @var HttpApi */
        $api = $this->getMockBuilder($apiClass, 'getContext')
            ->setMethods(['getContext'])
            // Disabling the constructor because it is not necessary for this test.
            ->disableOriginalConstructor()
            ->getMock();
        $api->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($concreteContext));

        $this->setRestrictedPropertyValues($api, [
            'config' => $config,
        ]);
        $this->assertSame($context, $this->invokeRestrictedMethod($api, 'addLogContext', [$key, $xml]));
    }
}
