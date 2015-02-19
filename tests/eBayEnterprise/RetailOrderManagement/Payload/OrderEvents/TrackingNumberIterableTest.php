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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\PayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator;
use Psr\Log\NullLogger;

class TrackingNumberIterableTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const FULL_FIXTURE_FILE = 'TrackingNumberIterable.xml';

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->payloadMap = new PayloadMap([
            '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ITrackingNumber' =>
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TrackingNumber'
        ]);

        $this->fullPayload = $this->buildPayload();
        $subpayload = $this->fullPayload->getEmptyTrackingNumber();
        $subpayload->setTrackingNumber('TRACKINGNUMBER1');
        $subpayload->setUrl('http://example.com/track/1');
        $this->fullPayload->offsetSet($subpayload);
        $subpayload = $this->fullPayload->getEmptyTrackingNumber();
        $subpayload->setTrackingNumber('TRACKINGNUMBER2');
        $subpayload->setUrl('http://example.com/track/2');
        $this->fullPayload->offsetSet($subpayload);
    }
    /**
     * Get a new LineItem payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @returnTrackingNumber
     */
    protected function createNewPayload()
    {
        return new TrackingNumberIterable($this->validatorIterator, $this->stubSchemaValidator, $this->payloadMap, new NullLogger());
    }

    /**
     * Test deserializing the data into payloads.
     */
    public function testDeserialize()
    {
        $payload = $this->buildPayload();
        $payload->deserialize($this->loadXmlTestString(__DIR__ . '/Fixtures/TrackingNumberIterable.xml'));

        // sub-payloads will contain some variation due to validators/dependent
        // objects, so test for only relevent data to match up between the
        // expected and actual
        $this->assertEquals($this->fullPayload->count(), $payload->count());

        $this->fullPayload->rewind();
        $payload->rewind();
        while ($this->fullPayload->valid()) {
            $expectedPayload = $this->fullPayload->current();
            $actualPayload = $payload->current();
            $this->assertEquals($expectedPayload->getTrackingNumber(), $actualPayload->getTrackingNumber());
            $this->assertEquals($expectedPayload->getUrl(), $actualPayload->getUrl());
            $this->fullPayload->next();
            $payload->next();
        }
    }

    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/' . static::FULL_FIXTURE_FILE;
    }

    public function testSerializeEmptyIterable()
    {
        $payload = $this->buildPayload();
        $this->assertSame(
            '<TrackingNumberList></TrackingNumberList>',
            $payload->serialize()
        );
    }
}
