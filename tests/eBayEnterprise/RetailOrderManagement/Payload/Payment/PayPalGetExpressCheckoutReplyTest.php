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
use eBayEnterprise\RetailOrderManagement\Payload;
use ReflectionProperty;

class PayPalGetExpressCheckoutReplyTest extends \PHPUnit_Framework_TestCase
{
    /** @var Payload\IValidator (stub) */
    protected $stubValidator;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var Payload\ISchemaValidator (stub) */
    protected $stubSchemaValidator;
    /** @var Payload\IPayloadMap (stub) */
    protected $stubPayloadMap;
    /** @var Payload\IPayloadFactory */
    protected $stubPayloadFactory;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->stubPayloadMap = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap');
        $this->stubPayloadFactory = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadFactory');
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInvalidPayload()
    {
        return [
            [[]] // Empty payload should fail validation.
        ];
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideValidPayload()
    {
        return [[[
            'setOrderId' => '0005400400154',
            'setResponseCode' => 'Success',
            'setPayerEmail' => 'tan_1329493113_per@trueaction.com',
            'setPayerId' => 'P9PMKWC782MJ8',
            'setPayerStatus' => 'verified',
            'setPayerPhone' => '848-129-8433',
            'setPayerCountry' => 'US',
            'setPayerNameHonorific' => '',
            'setPayerLastName' => 'Buyer',
            'setPayerMiddleName' => '',
            'setPayerFirstName' => 'TAN',
            'setBillingLines' => "Street 1\nStreet 2\nStreet 3\nStreet 4",
            'setBillingCity' => 'King of Prussia',
            'setBillingMainDivision' => 'PA',
            'setBillingCountryCode' => 'US',
            'setBillingPostalCode' => '19406',
            'setShipToLines' => "Street 1\nStreet 2\nStreet 3\nStreet 4",
            'setShipToCity' => 'King of Prussia',
            'setShipToMainDivision' => 'PA',
            'setShipToCountryCode' => 'US',
            'setShipToPostalCode' => '19406',
        ]]];
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
     * Create a payload with the provided data.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return PayPalGetExpressCheckoutReply
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();
        foreach ($properties as $propertySetter => $value) {
            $payload->$propertySetter($value);
        }
        return $payload;
    }

    /**
     * Get a new PayPalGetExpressCheckoutReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return PayPalGetExpressCheckoutReply
     */
    protected function createNewPayload()
    {
        $payload = new PayPalGetExpressCheckoutReply(
            $this->validatorIterator,
            $this->stubSchemaValidator,
            $this->stubPayloadMap
        );
        $reflection = new ReflectionProperty($payload, 'payloadFactory');
        $reflection->setAccessible(true);
        $reflection->setValue($payload, $this->stubPayloadFactory);
        return $payload;
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
        $domPayload = new DOMDocument();
        $domPayload->preserveWhiteSpace = false;
        $domPayload->loadXML($payload->serialize());

        $expectedDoc = new DOMDocument();
        $expectedDoc->preserveWhiteSpace = false;
        $expectedDoc->loadXML($this->loadXmlTestString());
        $this->assertEquals($expectedDoc->C14N(), $domPayload->C14N());
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function loadXmlTestString()
    {
        return $this->canonicalize(__DIR__ . "/Fixtures/PayPalGetExpressCheckoutReplyTest.xml");
    }

    /**
     * load an xml file and return the canonicalized string of its contents
     * @return string
     */
    protected function canonicalize($file)
    {
        $doc = new DOMDocument();
        $doc->preserveWhiteSpace = false;
        $doc->load($file);
        return $doc->C14N();
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
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function loadXmlInvalidTestString()
    {
        return $this->canonicalize(__DIR__ . "/Fixtures/InvalidPayPalGetExpressCheckoutReplyTest.xml");
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailPayloadInvalid()
    {
        $this->stubValidator
            ->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->loadXmlInvalidTestString();
        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);
    }

    /**
     * verify the payload deserializes given xml properly.
     * @dataProvider provideValidPayload
     */
    public function testDeserializePass(array $payloadData)
    {
        $expectedPayload = $this->buildPayload($payloadData);
        $xml = $this->loadXmlTestString();
        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);
        $this->assertEquals($expectedPayload, $newPayload);
    }
}
