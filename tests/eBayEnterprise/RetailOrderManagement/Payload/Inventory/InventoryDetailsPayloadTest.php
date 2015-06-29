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
use Psr\Log\NullLogger;

class InventoryDetailsPayloadTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const REQUEST = '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\InventoryDetailsRequest';
    const REPLY = '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\InventoryDetailsReply';
    const COMPLIANT_PAYLOAD = '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\CompliantShippingItem';
    const FULL_PAYLOAD = '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\ShippingItem';
    const SHIPPING_ITEM_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Inventory\IShippingItem';

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
            [static::REQUEST, __DIR__ . '/Fixtures/InventoryDetailsRequest-reduced.xml'],
            [static::REPLY, __DIR__ . '/Fixtures/InventoryDetailsReply-full.xml'],
            [static::REPLY, __DIR__ . '/Fixtures/InventoryDetailsReply-reduced.xml'],
        ];
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
     * Provide paths to fixture files containing valid serializations to use
     *
     * @return array
     */
    public function provideSerializedDataFileForFullRequest()
    {
        return [
            [static::FULL_PAYLOAD, __DIR__ . '/Fixtures/InventoryDetailsRequest-full.xml'],
            [static::COMPLIANT_PAYLOAD, __DIR__ . '/Fixtures/InventoryDetailsRequest-compliant.xml'],
        ];
    }

    /**
     * verify
     * - both the compliant and non-compliant shipping items deserialize the original full request xml successfully
     * - the compliant shipping item will never output the shipping method mode and display text
     * - the full shipping item will output the full request xml
     * @param  string
     * @param  string
     * @dataProvider provideSerializedDataFileForFullRequest
     */
    public function testRequestFullRequestPayload($shippingItemPayload, $outputFile)
    {
        $payload = static::REQUEST;
        $input = __DIR__ . '/Fixtures/InventoryDetailsRequest-full.xml';
        $this->injectShippingItemImplementation($shippingItemPayload);
        $payload = $this->createNewPayload($payload);
        // test deserializing the full request
        $serializedData = $this->loadXmlTestString($input);
        $payload->deserialize($serializedData);
        // test serializing the full request
        $serializedOutputData = $this->loadXmlTestString($outputFile);
        $this->assertSame($serializedOutputData, $payload->serialize());
    }

    /**
     * inject the desired payload implementation into the payload config map
     * for the IShipping interface for the details request.
     * @param string
     */
    protected function injectShippingItemImplementation($shippingItemPayload)
    {
        $property = new \ReflectionProperty($this->payloadFactory, 'payloadTypeMap');
        $property->setAccessible(true);
        $payloadTypeMap = $property->getValue($this->payloadFactory);
        $this->assertTrue(array_key_exists(static::REQUEST, $payloadTypeMap));
        $childPayloadTypes = &$payloadTypeMap[static::REQUEST]['childPayloads']['types'];
        $childPayloadTypes[static::SHIPPING_ITEM_INTERFACE] = $shippingItemPayload;
        $property->setValue($this->payloadFactory, $payloadTypeMap);
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
