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
}
