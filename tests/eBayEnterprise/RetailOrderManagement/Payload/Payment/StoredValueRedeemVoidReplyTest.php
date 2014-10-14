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

class StoredValueRedeemVoidReplyTest extends \PHPUnit_Framework_TestCase
{
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
        $this->validatorIterator = new Payload\ValidatorIterator(array($this->stubValidator));
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
    }

    /**
     * Get a new StoredValueRedeemVoidReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return StoredValueRedeemVoidReply
     */
    protected function createNewPayload()
    {
        return new StoredValueRedeemVoidReply($this->validatorIterator, $this->stubSchemaValidator);
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
                'cardNumber' => '4111ABC123ZYX987',
                'panIsToken' => false,
                'orderId' => '00000123456789',
            ]],
        ];
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideValidPayload()
    {
        $base = [
            'orderId' => '00000123456789',
            'cardNumber' => '4111ABC123ZYX987',
            'panIsToken' => false,
            'responseCode' => 'Success',
        ];
        return [
            [$base, ''],
        ];
    }

    /**
     * Create a payload with the provided data injected.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return StoredValueRedeemVoidReply
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();
        $payloadReflection = new \ReflectionClass($payload);
        foreach ($properties as $property => $value) {
            if ($payloadReflection->hasProperty($property)) {
                $property = $payloadReflection->getProperty($property);
                $property->setAccessible(true);
                $property->setValue($payload, $value);
            }
        }
        return $payload;
    }

    /**
     * load an xml file and return the canonicalized string of its contents
     * @return string
     */
    protected function canonicalize($file)
    {
        $doc = new \DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->load($file);
        return $doc->C14N();
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @param  string $case
     * @return string
     */
    protected function loadXmlTestString($case)
    {
        return $this->canonicalize(__DIR__ . "/Fixtures/StoredValueRedeemVoidReply{$case}.xml");
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
     * @param array  $payloadData
     * @param string $case
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData, $case)
    {
        $payload = $this->buildPayload($payloadData);
        $domPayload = new \DOMDocument();
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();

        $this->assertEquals($this->loadXmlTestString($case), $serializedString);
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
     * @param array  $payloadData
     * @param string $case
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData, $case)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->loadXmlTestString($case);
        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }
}
