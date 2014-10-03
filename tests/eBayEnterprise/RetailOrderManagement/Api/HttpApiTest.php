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

    public function setUp()
    {
        $this->requestPayloadStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IPayload');
        $this->replyPayloadStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IPayload');
        $config = new HttpConfig('key', 'host', 'major', 'minor', 'store', 'service', 'operation');
        $this->api = new HttpApi($config);

        // use reflection to inject mock payloads into the HttpApi object
        $reflection = new \ReflectionClass($this->api);
        $requestProperty = $reflection->getProperty('requestPayload');
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($this->api, $this->requestPayloadStub);

        $replyProperty = $reflection->getProperty('replyPayload');
        $replyProperty->setAccessible(true);
        $replyProperty->setValue($this->api, $this->replyPayloadStub);
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
        $this->replyPayloadStub->expects($this->any())
            ->method('deserialize')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $this->api->send();
    }
}
 