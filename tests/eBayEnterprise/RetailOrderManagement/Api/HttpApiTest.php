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

class HttpApiTest extends \PHPUnit_Framework_TestCase
{
    protected $requestPayloadStub;
    protected $replyPayloadStub;
    protected $api;
    protected $configStub;
    protected $apiStub;
    protected $requestsResponseStub;

    public function setUp()
    {
        $this->requestPayloadStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IPayload');
        $this->replyPayloadStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IPayload');
        $config = new HttpConfig('key', 'host', 'major', 'minor', 'store', 'service', 'operation');
        $this->api = new HttpApi($config);
        $this->injectPayloads($this->api, $this->requestPayloadStub, $this->replyPayloadStub);

        // create stubs for the config and api objects
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
        $this->injectPayloads($this->apiStub, $this->requestPayloadStub, $this->replyPayloadStub);

        $this->requestsResponseStub = $this->getMock('Requests_Response');
    }

    protected function injectPayloads($class, $requestPayload, $replyPayload)
    {
        // use reflection to inject mock payloads into the HttpApi object
        $reflection = new \ReflectionClass($class);
        $requestProperty = $reflection->getProperty('requestPayload');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($class, $requestPayload);

        $replyProperty = $reflection->getProperty('replyPayload');
        $replyProperty->setAccessible(true);
        $replyProperty->setValue($class, $replyPayload);
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
        $this->requestsResponseStub->success = true;
        $this->apiStub->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($this->requestsResponseStub));
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
        $this->requestsResponseStub->success = false;
        $this->requestsResponseStub->status_code = 404;
        $this->requestsResponseStub->url = 'http://no.such.url';
        $this->apiStub->expects($this->any())
            ->method('sendRequest')
            ->will($this->returnValue($this->requestsResponseStub));
        $this->apiStub->send();
    }

    /**
     * @expectedException Requests_Exception
     */
    public function testSendHandlesRequestsException()
    {
        $this->apiStub->expects($this->any())
            ->method('sendRequest')
            ->will($this->throwException(new \Requests_Exception_HTTP));
        $this->apiStub->send();
    }
}
 