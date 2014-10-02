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

class CreditCardAuthRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Payload\IValidator */
    protected $validatorStub;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var  Payload\ISchemaValidator */
    protected $schemaValidatorStub;

    protected function setUp()
    {
        $this->validatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator(array($this->validatorStub));
        $this->schemaValidatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
    }

    /**
     * data provider to provide an empty array of properties
     * Empty properties will generate and invalid IPayload object
     *
     * @return array $payloadData
     */
    public function provideInvalidPayload()
    {
        $payloadData = array();

        return array(
            array($payloadData)
        );
    }

    /**
     * Data provider to provide an array of valid property values that will generate an valid IPayload object
     *
     * @return array $payloadData
     */
    public function provideValidPayload()
    {
        // move to JSON
        $properties = array(
            'setRequestId' => '739a45ba35',
            'setOrderId' => 'testOrderId',
            'setPanIsToken' => false,
            'setCardNumber' => '4111111111111111',
            'setExpirationDate' => date_create('2015-12', new \DateTimeZone('UTC')),
            'setCardSecurityCode' => '123',
            'setAmount' => 43.45,
            'setCurrencyCode' => 'USD',
            'setEmail' => 'test@example.com',
            'setIp' => '127.0.0.1',
            'setBillingFirstName' => 'First',
            'setBillingLastName' => 'Last',
            'setBillingPhone' => '123-456-7890',
            'setBillingLines' => 'Street 1\nStreet 2\nStreet 3\nStreet 4',
            'setBillingCity' => 'King of Prussia',
            'setBillingMainDivision' => 'PA',
            'setBillingCountryCode' => 'US',
            'setBillingPostalCode' => '19406',
            'setShipToFirstName' => 'First',
            'setShipToLastName' => 'Last',
            'setShipToPhone' => '123-456-7890',
            'setShipToLines' => 'Street 1\nStreet 2\nStreet 3\nStreet 4',
            'setShipToCity' => 'King of Prussia',
            'setShipToMainDivision' => 'PA',
            'setShipToCountryCode' => 'US',
            'setShipToPostalCode' => '19406',
            'setIsRequestToCorrectCvvOrAvsError' => false,
            'setAuthenticationAvailable' => 'Y',
            'setAuthenticationStatus' => 'Y',
            'setCavvUcaf' => 'abcd1234',
            'setTransactionId' => 'transId',
            'setEci' => 'ECI',
            'setPayerAuthenticationResponse' => 'some REALLY big string'
        );

        return array(
            array($properties)
        );
    }

    /**
     * Take an array of property values with property names as keys and return an IPayload object
     *
     * @param array $properties
     * @return CreditCardAuthRequest
     */
    protected function buildPayload(array $properties)
    {
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);

        foreach ($properties as $property => $value) {
            $payload->$property($value);
        }

        return $payload;
    }

    /**
     * Read an XML file with valid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlTestString()
    {
        $dom = new \DOMDocument();
        $dom->load(__DIR__.'/Fixtures/CreditCardAuthRequest.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * Read an XML file with invalid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlInvalidTestString()
    {
        $dom = new \DOMDocument();
        $dom->load(__DIR__.'/Fixtures/InvalidCreditCardAuthRequest.xml');
        $string = $dom->C14N();

        return $string;
    }

    public function testCleanStringCleansValidString()
    {
        $value = 'testReqId';
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanString');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($value, 40));
        $this->assertSame($value, $cleaned);
    }

    public function testCleanStringDetectsNonString()
    {
        $value = 100;
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanString');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($value, 40));
        $this->assertSame(null, $cleaned);
    }

    public function testCleanStringTruncatesString()
    {
        $value = 'abcdefghijklmnopqrstuvwxyz';
        $truncated = 'abcde';
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanString');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($value, 5));
        $this->assertSame($truncated, $cleaned);
    }

    public function testCleanStringNoMaxLengthReturnsFullString()
    {
        $value = 'abcdefghijklmnopqrstuvwxyz';
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanString');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($value));
        $this->assertSame($value, $cleaned);
    }

    public function testCleanAddressLinesHandlesValidString()
    {
        $lines = 'Street 1\nStreet 2\n Street 3\nStreet 4';
        $correct = array('Street 1', 'Street 2', 'Street 3', 'Street 4');
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanAddressLines');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($lines));
        $this->assertSame($correct, $cleaned);
    }

    public function testCleanAddressLinesHandlesExtraLines()
    {
        $lines = 'Street 1\nStreet 2\n Street 3\nStreet 4\nStreet 5';
        $correct = array('Street 1', 'Street 2', 'Street 3', 'Street 4Street 5');
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanAddressLines');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($lines));
        $this->assertSame($correct, $cleaned);
    }

    public function testCleanAddressLinesDetectsNoString()
    {
        $value = 100;
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanAddressLines');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($value));
        $this->assertSame(null, $cleaned);
    }

    /**
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testValidateWillFail(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload->validate();
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testValidateWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $this->assertSame($payload, $payload->validate());
    }

    /**
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFail(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->schemaValidatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload()));
        $payload->serialize();
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->schemaValidatorStub->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $domPayload = new \DOMDocument();
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();

        $this->assertEquals($this->xmlTestString(), $serializedString);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFail()
    {
        $xml = $this->xmlInvalidTestString();

        $newPayload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $newPayload->deserialize($xml);
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->xmlTestString();

        $newPayload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }
}
