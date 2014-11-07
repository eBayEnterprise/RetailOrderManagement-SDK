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

use eBayEnterprise\RetailOrderManagement\Payload;
use eBayEnterprise\RetailOrderManagement\Util\TTestReflection;

class StoredValueBalanceReplyTest extends \PHPUnit_Framework_TestCase
{
    use TTestReflection;

    /** @var Payload\IValidator (stub) */
    protected $stubValidator;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var Payload\ISchemaValidator (stub) */
    protected $stubSchemaValidator;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
    }

    /**
     * Get a new StoredValueBalanceReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return StoredValueBalanceReply
     */
    protected function createNewPayload()
    {
        return new StoredValueBalanceReply($this->validatorIterator, $this->stubSchemaValidator);
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInvalidPayload()
    {
        return [
            [[]], // Empty payload should fail validation.
            [[
                'cardNumber' => 'RvS1kwB3eCxzk5lI',
                'panIsToken' => false,
                'responseCode' => 'glom', // Invalid response code
                'currencyCode' => 'USD',
            ]],
        ];
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideValidPayload()
    {
        return [[[
            'cardNumber' => 'KDVXYXCeFCG8GfH6',
            'panIsToken' => true,
            'balanceAmount' => 87.44,
            'currencyCode' => 'USD',
            'responseCode' => 'Success',
        ]]];
    }

    /**
     * Data provider for success and failure tests
     * @return array[]
     */
    public function provideResponseCodeConditions()
    {
        return [[[
            'cardNumber' => 'KDVXYXCeFCG8GfH6',
            'panIsToken' => true,
            'balanceAmount' => 87.44,
            'currencyCode' => 'USD',
            'responseCode' => 'Success',
        ]], [[
            'cardNumber' => 'KDVXYXCeFCG8GfH6',
            'panIsToken' => true,
            'balanceAmount' => 87.44,
            'currencyCode' => 'USD',
            'responseCode' => 'Failure',
        ]], [[
            'cardNumber' => 'KDVXYXCeFCG8GfH6',
            'panIsToken' => true,
            'balanceAmount' => 87.44,
            'currencyCode' => 'USD',
            'responseCode' => 'Timeout',
        ]]];
    }

    /**
     * Create a payload with the provided data injected.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return StoredValueBalanceReply
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();
        $this->setRestrictedPropertyValues($payload, $properties);
        return $payload;
    }

    /**
     * Load the XML from a fixture file and canonicalize it. Returns the
     * canonical XML string.
     * @return string
     */
    protected function loadXmlTestString()
    {
        return file_get_contents(__DIR__ . '/Fixtures/StoredValueBalanceReply.xml');
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function loadXmlInvalidTestString()
    {
        return file_get_contents(__DIR__ . '/Fixtures/InvalidStoredValueBalanceReply.xml');
    }

    /**
     * Simply ensure that when one validator fails validation, the exception
     * is thrown - is not validating the actual payload data.
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testValidateWillFail(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        // script the validator to fail validation
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload->validate();
    }

    /**
     * Simply ensure that when none of the validators fail, the payload is
     * considered valid - is not validating actual payload data.
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testValidateWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        // script the validator to pass validation
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $this->assertSame($payload, $payload->validate());
    }

    /**
     * Tests that serialize will perform validation. Should any validator
     * fail, serialization should also fail.
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailWithInvalidPayload(array $payloadData)
    {
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload = $this->buildPayload($payloadData);
        $payload->serialize();
    }

    /**
     * Test that if a payload should pass validation but still produce an
     * XSD invalid payload, serialization should fail.
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailWithXsdInvalidPayloadData(array $payloadData)
    {
        $this->stubSchemaValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload = $this->buildPayload($payloadData);
        $payload->serialize();
    }
    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $domPayload = new \DOMDocument();
        $domPayload->preserveWhiteSpace = false;
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();
        $domPayload->loadXML($this->loadXmlTestString());
        $expectedString = $domPayload->C14N();

        $this->assertSame($expectedString, $serializedString);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailSchemaInvalid()
    {
        $this->stubSchemaValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->loadXmlInvalidTestString();

        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailPayloadInvalid()
    {
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->loadXmlInvalidTestString();

        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->loadXmlTestString();
        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }

    /**
     * Test that a response code of "Success" is considered a successful balance request/reply.
     * @dataProvider provideResponseCodeConditions
     */
    public function testIsSuccess(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $isSuccessful = $payload->isSuccessful();
        if ($payload->getResponseCode() === 'Success') {
            $this->assertTrue($isSuccessful);
        } else {
            $this->assertFalse($isSuccessful);
        }
    }
}
