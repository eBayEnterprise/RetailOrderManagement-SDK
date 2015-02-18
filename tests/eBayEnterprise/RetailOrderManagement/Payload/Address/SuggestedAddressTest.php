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

class SuggestedAddressTest extends \PHPUnit_Framework_TestCase
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
     * suggested address payloads.
     *
     * @return array
     */
    public function provideSerializedDataFile()
    {
        return [
            [__DIR__ . '/Fixtures/SuggestedAddress.xml'],
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
        $this->assertSame($this->loadXmlTestString($serializedDataFile, true), $payload->serialize());
    }

    /**
     * Get a new SuggestedAddress payload.
     * @return SuggestedAddress
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Address\SuggestedAddress');
    }
}
