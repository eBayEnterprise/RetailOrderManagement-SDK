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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\NullLogger;

class TaxDutyFeeQuotePayloadTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const REQUEST = '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteRequest';
    const REPLY = '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteReply';

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
    }

    /**
     * Provide paths to fixture files containing valid serializations to use
     *
     * @return array
     */
    public function provideSerializedDataFile()
    {
        return [
            [static::REQUEST, __DIR__ . '/Fixtures/TaxDutyFeeQuoteRequest-reduced.xml'],
            [static::REQUEST, __DIR__ . '/Fixtures/TaxDutyFeeQuoteRequest-multiship.xml'],
            [static::REQUEST, __DIR__ . '/Fixtures/TaxDutyFeeQuoteRequest-full.xml'],
            [static::REPLY, __DIR__ . '/Fixtures/TaxDutyFeeQuoteReply-reduced.xml'],
            [static::REPLY, __DIR__ . '/Fixtures/TaxDutyFeeQuoteReply-multiship.xml'],
            [static::REPLY, __DIR__ . '/Fixtures/TaxDutyFeeQuoteReply-full.xml'],
        ];
    }

    /**
     * Provide paths to fixture files containing valid serializations to use
     *
     * @return array
     */
    public function provideOutOfOrderDestinationSerializedDataFile()
    {
        return [
            [static::REQUEST, __DIR__ . '/Fixtures/TaxDutyFeeQuoteRequest-reduced.xml'],
            [static::REPLY, __DIR__ . '/Fixtures/TaxDutyFeeQuoteReply-reduced.xml'],
        ];
    }

    /**
     * Test serializing out of order destinations
     *
     * @param string payload fully qualified name
     * @param string path to fixture file
     * @dataProvider provideOutOfOrderDestinationSerializedDataFile
     * @medium
     */
    public function testSerializeOutOfOrderDestinations($payload, $serializedDataFile)
    {
        $payload = $this->createNewPayload($payload);
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);

        $destinations = $payload->getDestinations();
        $address = $destinations->getEmptyMailingAddress();
        $address->setFirstName('foo')
            ->setLastName('foo')
            ->setLines('1 test st')
            ->setCity('somecity')
            ->setCountryCode('USA');
        $destinations->attach($address);
        // if serialization doesn't trigger an xsd validation this test passes
        $this->assertNotEmpty($payload->serialize());
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string payload fully qualified name
     * @param string path to fixture file
     * @dataProvider provideSerializedDataFile
     * @medium
     */
    public function testDeserializeSerialize($payload, $serializedDataFile)
    {
        $payload = $this->createNewPayload($payload);
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
        $this->assertSame($serializedData, $payload->serialize());
    }

    /**
     * Get a new TaxDutyFeeQuote payload.
     * @return IPayload
     */
    protected function createNewPayload($payload)
    {
        return $this->payloadFactory
            ->buildPayload($payload, null, null, new NullLogger());
    }
}
