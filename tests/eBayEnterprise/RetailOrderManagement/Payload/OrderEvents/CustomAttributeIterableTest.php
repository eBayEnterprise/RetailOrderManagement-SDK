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

class CustomAttributeIterableTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const FULL_FIXTURE_FILE = 'CustomAttributeIterable.xml';

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
            '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\ICustomAttribute' =>
                '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\CustomAttribute'
        ]);

        $this->fullPayload = $this->buildPayload();
        $subpayload = $this->fullPayload->getEmptyCustomAttribute();
        $subpayload->setKey('key');
        $subpayload->setValue('value');
        $this->fullPayload->offsetSet($subpayload);
        $subpayload = $this->fullPayload->getEmptyCustomAttribute();
        $subpayload->setKey('key 2');
        $subpayload->setValue('value 2');
        $this->fullPayload->offsetSet($subpayload);
    }
    /**
     * Get a new LineItem payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return CustomAttribute
     */
    protected function createNewPayload()
    {
        return new CustomAttributeIterable($this->validatorIterator, $this->stubSchemaValidator, $this->payloadMap);
    }

    /**
     * Test deserializing the data into payloads.
     */
    public function testDeserialize()
    {
        $payload = $this->buildPayload();
        $payload->deserialize($this->loadXmlTestString(__DIR__ . '/Fixtures/CustomAttributeIterable.xml'));

        // sub-payloads will contain some variation due to validators/dependent
        // objects, so test for only relevent data to match up between the
        // expected and actual
        $this->assertEquals($this->fullPayload->count(), $payload->count());

        $this->fullPayload->rewind();
        $payload->rewind();
        while ($this->fullPayload->valid()) {
            $expectedPayload = $this->fullPayload->current();
            $actualPayload = $payload->current();
            $this->assertEquals($expectedPayload->getKey(), $actualPayload->getKey());
            $this->assertEquals($expectedPayload->getValue(), $actualPayload->getValue());
            $this->fullPayload->next();
            $payload->next();
        }
    }

    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/' . static::FULL_FIXTURE_FILE;
    }
}
