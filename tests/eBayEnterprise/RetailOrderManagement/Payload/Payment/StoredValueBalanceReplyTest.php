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
use eBayEnterprise\RetailOrderManagement\Util\TTestReflection;
use Psr\Log\NullLogger;

class StoredValueBalanceReplyTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest, TTestReflection;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
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
                __DIR__ . '/Fixtures/StoredValueBalanceReply.xml'
            ],
        ];
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string path to fixture file
     * @dataProvider provideSerializedDataFile
     */
    public function testDeserializeSerialize($serializedDataFile)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
        $this->assertSame($serializedData, $payload->serialize());
    }

    /**
     * Data provider for success and failure tests
     * @return array[]
     */
    public function provideResponseCodeConditions()
    {
        return [
            [
                [
                    'setCardNumber' => 'KDVXYXCeFCG8GfH6',
                    'setPanIsToken' => true,
                    'setBalanceAmount' => 87.44,
                    'setCurrencyCode' => 'USD',
                    'setResponseCode' => 'Success',
                ]
            ],
            [
                [
                    'setCardNumber' => 'KDVXYXCeFCG8GfH6',
                    'setPanIsToken' => true,
                    'setBalanceAmount' => 87.44,
                    'setCurrencyCode' => 'USD',
                    'setResponseCode' => 'Failure',
                ]
            ],
            [
                [
                    'setCardNumber' => 'KDVXYXCeFCG8GfH6',
                    'setPanIsToken' => true,
                    'setBalanceAmount' => 87.44,
                    'setCurrencyCode' => 'USD',
                    'setResponseCode' => 'Timeout',
                ]
            ]
        ];
    }

    /**
     * Test that a response code of "Success" is considered a successful balance request/reply.
     * @dataProvider provideResponseCodeConditions
     */
    public function testIsSuccess(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $isSuccessful = $payload->isSuccessful();
        if ($payload->getResponseCode() === 'Success') {
            $this->assertTrue($isSuccessful);
        } else {
            $this->assertFalse($isSuccessful);
        }
    }

    /**
     * Get a new StoredValueBalanceReply payload.
     * @return StoredValueBalanceReply
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceReply', null, null, new NullLogger());
    }
}
