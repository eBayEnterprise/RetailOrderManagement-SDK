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
use eBayEnterprise\RetailOrderManagement\Payload\TpayloadTest;

class StoredValueRedeemVoidRequestTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    /**
     * Setup a stub validator and validator iterator for each payload to use
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
                __DIR__ . "/Fixtures/StoredValueRedeemVoidRequest.xml"
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
     * Provide paths to fixutre files containing valid serializations of
     * order shipped payloads.
     *
     * @return array
     */
    public function provideInvalidSerializedDataFile()
    {
        return [
            [
                __DIR__ . "/Fixtures/InvalidStoredValueRedeemVoidRequest.xml"
            ],
        ];
    }

    /**
     * Test deserializing invalid data will throw an InvalidPayload Exception
     *
     * @param string path to fixture file
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     * @dataProvider provideInvalidSerializedDataFile
     */
    public function testDeserializeInvalidPayload($serializedDataFile)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
    }

    /**
     * Get a new StoredValueRedeemVoidRequest payload.
     * @return StoredValueRedeemVoidRequest
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest');
    }
}
