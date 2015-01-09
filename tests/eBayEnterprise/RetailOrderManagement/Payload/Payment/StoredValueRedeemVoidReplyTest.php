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

class StoredValueRedeemVoidReplyTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    /**
     * Setup payload factory to create the order shipped payloads to test.
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory;
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInvalidPayload()
    {
        return [
            [[]], // Empty payload should fail validation.
            [
                [
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setPanIsToken' => false,
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                ]
            ],
        ];
    }

    /**
     * Data provider for was voided tests
     * @return array[]
     */
    public function provideResponseCodeConditions()
    {
        return [
            [
                [
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setPanIsToken' => false,
                    'setResponseCode' => 'Success',
                ]
            ],
            [
                [
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setPanIsToken' => false,
                    'setResponseCode' => 'Fail',
                ]
            ],
            [
                [
                    'setOrderId' => 'o3trodZDaS2zhZHirJnA',
                    'setCardNumber' => 'hmrROxcsoE8BDmbZFUME0+',
                    'setPanIsToken' => false,
                    'setResponseCode' => 'Timeout',
                ]
            ]
        ];
    }

    /**
     * Get a new StoredValueRedeemVoidReply payload.
     * @return StoredValueRedeemVoidReply
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidReply');
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function loadXmlInvalidTestString()
    {
        return $this->canonicalize(__DIR__ . "/Fixtures/InvalidStoredValueRedeemVoidReply.xml");
    }

    /**
     * Test that a response code of "Success" is considered a successful redeem void request/reply.
     * @dataProvider provideResponseCodeConditions
     */
    public function testWasVoided(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $wasVoided = $payload->wasVoided();
        if ($payload->getResponseCode() === 'Success') {
            $this->assertTrue($wasVoided);
        } else {
            $this->assertFalse($wasVoided);
        }
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
                __DIR__ . '/Fixtures/StoredValueRedeemVoidReply.xml'
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
}
