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

use eBayEnterprise\RetailOrderManagement\Payload;

class PayloadFactoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var  \eBayEnterprise\RetailOrderManagement\Api\IConfig */
    protected $configStub;
    /** @var  IValidator */
    protected $validatorStub;
    /** @var  IValidatorIterator */
    protected $validatorIterator;
    public function setUp()
    {
        $this->configStub = $this->getMock('eBayEnterprise\RetailOrderManagement\Api\IConfig');
        $this->validatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator(array($this->validatorStub));
    }

    public function provideRequestPayloadData()
    {
       return [
           ['payments', 'creditcard/auth/VC', '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest']
       ];
    }

    public function provideReplyPayloadData()
    {
        return [
            ['payments', 'creditcard/auth/VC', '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply']
        ];
    }

    public function provideInvalidConfigData()
    {
        return [
            ['payments', 'giftcard/auth/VC']
        ];
    }

    /**
     * @param $serviceOperation
     * @param $payloadType
     * @dataProvider provideRequestPayloadData
     */
    public function testRequestPayload($service, $operation, $payloadType)
    {
        $this->configStub->expects($this->any())
            ->method('getServiceOperation')
            ->will($this->returnValue([$service, $operation]));
        $factory = new PayloadFactory($this->configStub);
        $this->assertInstanceOf($payloadType, $factory->requestPayload());
    }

    /**
     * @param $serviceOperation
     * @param $payloadType
     * @dataProvider provideReplyPayloadData
     */
    public function testReplyPayload($service, $operation, $payloadType)
    {
        $this->configStub->expects($this->any())
            ->method('getServiceOperation')
            ->will($this->returnValue([$service, $operation]));
        $factory = new PayloadFactory($this->configStub);
        $this->assertInstanceOf($payloadType, $factory->replyPayload());
    }

    /**
     * @param $serviceOperation
     * @dataProvider provideInvalidConfigData
     * @expectedException \eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedOperation
     */
    public function testInvalidConfig($service, $operation)
    {
        $this->configStub->expects($this->any())
            ->method('getServiceOperation')
            ->will($this->returnValue([$service, $operation]));
        $factory = new PayloadFactory($this->configStub);
        $this->assertNull($factory->requestPayload());
        $this->assertNull($factory->replyPayload());
    }
}
 