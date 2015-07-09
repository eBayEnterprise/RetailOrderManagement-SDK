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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;

class TenderTypeLookupReplyTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
    }

    /**
     * Provide paths to fixutre files containing valid, serialized
     * tender type lookup replies.
     *
     * @return array
     */
    public function provideSerializedDataFile()
    {
        return [
            [__DIR__ . '/Fixtures/tendertypelookupreply.xml'],
        ];
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string path to fixture file
     * @dataProvider provideSerializedDataFile
     * @medium
     */
    public function testDeserializeSerialize($serializedDataFile)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
        $this->assertSame($serializedData, $payload->serialize());
    }

    /**
     * Provide paths to fixutre files containing invalid, serialized
     * tender type lookup replies.
     *
     * @return array
     */
    public function provideInvalidSerializedDataFile()
    {
        return [
            [__DIR__ . '/Fixtures/tendertypelookupreply-invalidresponsecode.xml'],
        ];
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string path to fixture file
     * @dataProvider provideInvalidSerializedDataFile
     * @medium
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testInvalidPayload($serializedDataFile)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
    }

    public function provideUnsuccessfulResponseCodes()
    {
        return [
            [TENDER_TYPE_FOUND, true],
            [PAN_FAILS_LUHN_CHECK, false],
            [NO_TENDER_TYPE_FOUND, false],
            [PAN_NOT_CONFIGURED_TO_STORE, false],
            [UNKNOWN_FAILURE, false],
            ['randomvalue', false],
        ];
    }

    /**
     * Scenario: checking if the lookup operation was successful
     * Given: a LookupReply payload with a non-empty/non-null response code
     *
     * WHEN: the response code is TENDER_TYPE_FOUND
     * THEN: isSuccessful returns true.
     * WHEN: the response code is not TENDER_TYPE_FOUND
     * THEN: isSuccessful returns false
     *
     * @param string
     * @param bool
     * @dataProvider provideUnsuccessfulResponseCodes
     */
    public function testIsSuccessful($responseCode, $isSuccessful)
    {
        $payload = $this->createNewPayload();
        $payload->setResponseCode($responseCode);
        $this->assertSame($isSuccessful, $payload->isSuccessful());
    }

    /**
     * Get a new ValidationRequest payload.
     * @return ValidationRequest
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Payment\TenderType\LookupReply');
    }
}
