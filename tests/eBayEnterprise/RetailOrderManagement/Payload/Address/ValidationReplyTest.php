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

namespace eBayEnterprise\RetailOrderManagement\Payload\Address;

use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;

class ValidationReplyTest extends \PHPUnit_Framework_TestCase
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
     * Provide paths to fixutre files containing valid serializations of
     * address validation reply payloads.
     *
     * @return array
     */
    public function provideSerializedDataFile()
    {
        return [
            [__DIR__ . '/Fixtures/ValidationReply.xml'],
            [__DIR__ . '/Fixtures/ValidationReplyMinimal.xml'],
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
     * Get a new ValidationReply payload.
     * @return ValidationReply
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Address\ValidationReply');
    }

    /**
     * Provides address validation reply data and whether that data should result
     * in a valid reply.
     *
     * @return array
     */
    public function provideReplyValidationData()
    {
        return [
            ['V', false, true],
            ['C', false, true],
            ['C', true, false],
            ['P', true, false],
        ];
    }

    /**
     * If a reply has a successful result code, or a conditionally successful
     * result code and no suggestions, the reply should be considered valid.
     *
     * @param string
     * @param bool
     * @param bool
     * @dataProvider provideReplyValidationData
     */
    public function testValidReply($resultCode, $hasSuggestions, $isValid)
    {
        $payload = $this->createNewPayload();
        $payload->setResultCode($resultCode);
        if ($hasSuggestions) {
            $suggestions = $payload->getSuggestedAddresses();
            $address = $suggestions->getEmptySuggestedAddress();
            $suggestions->offsetSet($address);
            $payload->setSuggestedAddresses($suggestions);
        }
        $this->assertSame($isValid, $payload->isValid());
    }

    /**
     * Provide address validation reply data and whether that data should result
     * in an acceptable reply.
     *
     * @return array
     */
    public function provideReplyAcceptableData()
    {
        return [
            ['V', false, true],
            ['N', false, true],
            ['C', false, true],
            ['U', false, true],
            ['T', false, true],
            ['P', false, true],
            ['M', false, true],
            ['K', false, false],
            ['C', true, false],
        ];
    }

    /**
     * If a reply is valid or has a response code indicating the address validation
     * provider is unable to validate the address, either as valid or invalid,
     * the reply should be considered to be acceptable.
     *
     * @param string
     * @param bool
     * @param bool
     * @dataProvider provideReplyAcceptableData
     */
    public function testAcceptableReply($resultCode, $hasSuggestions, $isAcceptable)
    {
        $payload = $this->createNewPayload();
        $payload->setResultCode($resultCode);
        if ($hasSuggestions) {
            $suggestions = $payload->getSuggestedAddresses();
            $address = $suggestions->getEmptySuggestedAddress();
            $suggestions->offsetSet($address);
            $payload->setSuggestedAddresses($suggestions);
        }
        $this->assertSame($isAcceptable, $payload->isAcceptable());
    }
}
