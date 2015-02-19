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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

use DOMDocument;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use Psr\Log\NullLogger;

class PayPalDoAuthorizationReplyTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    /**
     * Setup payload factory to create the order shipped payloads to test.
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory;
    }

    /**
     * Provide paths to fixutre files containing valid serializations of
     * order shipped payloads.
     *
     * @return array
     */
    public function provideSerializedDataFile()
    {
        return [
            [
                __DIR__ . '/Fixtures/PayPalDoAuthorizationReplyTest.xml',
                true // isSuccess Expectation
            ],
        ];
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string path to fixture file
     * @param bool   expectation for the isSuccess method
     * @dataProvider provideSerializedDataFile
     */
    public function testDeserializeSerialize($serializedDataFile, $isSuccess)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
        $this->assertSame($isSuccess, $payload->isSuccess());
        $this->assertSame($serializedData, $payload->serialize());
    }

    /**
     * Construct a new PayPalDoAuthorizationReply payload.
     *
     * @return IPayload
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationReply', null, null, new NullLogger());
    }
}
