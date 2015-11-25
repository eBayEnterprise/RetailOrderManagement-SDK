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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use DOMDocument;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\NullLogger;

class OrderDetailResponseTest extends \PHPUnit_Framework_TestCase
{
    /** @var PayloadFactory */
    protected $payloadFactory;

    /**
     * Setup payload factory to create the order Backorder payloads to test.
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
    }

    /**
     * Create a new payload and set any data passed in the properties parameter.
     * Each key in array should be a setter method to call and will be given
     * the value at that key.
     *
     * @param array
     * @return IPayload
     */
    protected function buildPayload(array $properties = [])
    {
        $payload = $this->createNewPayload();

        foreach ($properties as $setterMethod => $value) {
            $payload->$setterMethod($value);
        }
        return $payload;
    }

    /**
     * Create a new OrderDetailResponse payload.
     *
     * @return IPayload
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\OrderDetailResponse', null, null, new NullLogger());
    }

    /**
     * Return a C14N, whitespace removed, XML string.
     */
    protected function loadXmlTestString($fixtureFile)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->load($fixtureFile);
        $string = $dom->C14N();

        return $string;
    }

    /**
     * Provide paths to fixture files containing valid serializations of
     * order Backorder payloads.
     *
     * @return array
     */
    public function provideOrderDetailResponseSerializedDataFile()
    {
        return [
            [__DIR__ . '/Fixtures/OrderDetailResponse.xml'],
            [__DIR__ . '/Fixtures/OrderDetailResponseMinimalData.xml'],
            [__DIR__ . '/Fixtures/OrderDetailResponseProxyPickupNoAddress.xml'],
        ];
    }

    /**
     * Test deserializing data into a payload and then deserializing back
     * to match the original data.
     *
     * @param string path to fixture file
     * @dataProvider provideOrderDetailResponseSerializedDataFile
     */
    public function testOrderDetailResponseDeserializeSerialize($serializedDataFile)
    {
        $payload = $this->buildPayload();
        $serializedData = $this->loadXmlTestString($serializedDataFile);
        $payload->deserialize($serializedData);
        $this->assertSame($serializedData, $payload->serialize());
    }
}
