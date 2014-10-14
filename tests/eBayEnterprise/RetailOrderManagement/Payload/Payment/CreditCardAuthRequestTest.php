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
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /** @var  Payload\IValidator */
    protected $validatorStub;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var  Payload\ISchemaValidator */
    protected $schemaValidatorStub;
    /** @var string */
    protected $testXML = '<Root xmlns="http://api.gsicommerce.com/schema/checkout/1.0"><Node1 attrib="true">0</Node1><Node2  attrib="false">1</Node2></Root>';

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
     * @return array
     */
    public function provideBooleanFromStringTests()
    {
        return array(
            array("true", true),
            array("false", false),
            array("1", true),
            array("0", false),
            array("True", true),
            array(null, null),
            array(1, null),
            array("test", null)
        );
    }

    /**
     * Provide test data for the cleanStrings function
     * @return array
     */
    public function provideCleanStringTests()
    {
        return array(
            // good data
            array('testReqId', 40, 'testReqId'),
            // not a string
            array(100, 40, null),
            // properly truncates
            array('abcdefghijklmnopqrstuvwxyz', 5, 'abcde')
        );
    }

    /**
     * Provide test data for the cleanAddressLines function
     * @return array
     */
    public function provideCleanAddressLinesTests()
    {
        return array(
            array(  // good data
                'Street 1\nStreet 2\n Street 3\nStreet 4',
                array('Street 1', 'Street 2', 'Street 3', 'Street 4')
            ),
            array(  // extra lines
                'Street 1\nStreet 2\n Street 3\nStreet 4\nStreet 5',
                array('Street 1', 'Street 2', 'Street 3', 'Street 4 Street 5')
            ),
            array( // not a string
                100,
                null
            )
        );
    }

    /**
     * Provide test data to verify serializeSecureVerificationData
     */
    public function provideVerificationData()
    {
        return [
            [
                // all fields present
                [
                    'authenticationAvailable' => 'Y',
                    'authenticationStatus' => 'Y',
                    'cavvUcaf' => 'abcd1234',
                    'transactionId' => 'transId',
                    'eci' => 'ECI',
                    'payerAuthenticationResponse' => 'some REALLY big string'
                ],
                // full optional group returned
                '<SecureVerificationData><AuthenticationAvailable>Y</AuthenticationAvailable><AuthenticationStatus>Y</AuthenticationStatus><CavvUcaf>abcd1234</CavvUcaf><TransactionId>transId</TransactionId><ECI>ECI</ECI><PayerAuthenticationResponse>some REALLY big string</PayerAuthenticationResponse></SecureVerificationData>'
            ],
            [
                // optional field missing - OK
                [
                    'authenticationAvailable' => 'Y',
                    'authenticationStatus' => 'Y',
                    'cavvUcaf' => 'abcd1234',
                    'transactionId' => 'transId',
                    'payerAuthenticationResponse' => 'some REALLY big string'
                ],
                // optional group w/o optional node
                '<SecureVerificationData><AuthenticationAvailable>Y</AuthenticationAvailable><AuthenticationStatus>Y</AuthenticationStatus><CavvUcaf>abcd1234</CavvUcaf><TransactionId>transId</TransactionId><PayerAuthenticationResponse>some REALLY big string</PayerAuthenticationResponse></SecureVerificationData>'
            ],
            [
                // required field mising -
                [
                    'authenticationAvailable' => 'Y',
                    'authenticationStatus' => 'Y',
                    'cavvUcaf' => 'abcd1234',
                    'transactionId' => '',
                    'eci' => 'ECI',
                    'payerAuthenticationResponse' => 'some REALLY big string'
                ],
                // skip optional group
                ''
            ]
        ];
    }

    /**
     * Provide test data to verify serializeShippingAddress
     */
    public function provideShippingAddressData()
    {
        return [
            [
                // optional fields present
                [
                    'shipToLines' => [
                        'Street 1',
                        'Street 2',
                        'Street 3',
                        'Street 4'
                    ],
                    'shipToCity' => 'King of Prussia',
                    'shipToMainDivision' => 'PA',
                    'shipToCountryCode' => 'US',
                    'shipToPostalCode' => '19406'
                ],
                // full section returned
                '<ShippingAddress><Line1>Street 1</Line1><Line2>Street 2</Line2><Line3>Street 3</Line3><Line4>Street 4</Line4><City>King of Prussia</City><MainDivision>PA</MainDivision><CountryCode>US</CountryCode><PostalCode>19406</PostalCode></ShippingAddress>'
            ],
            [
                // mainDivision missing
                [
                    'shipToLines' => [
                        'Street 1',
                        'Street 2',
                        'Street 3',
                        'Street 4'
                    ],
                    'shipToCity' => 'King of Prussia',
                    'shipToCountryCode' => 'US',
                    'shipToPostalCode' => '19406'
                ],
                // skip mainDivision node
                '<ShippingAddress><Line1>Street 1</Line1><Line2>Street 2</Line2><Line3>Street 3</Line3><Line4>Street 4</Line4><City>King of Prussia</City><CountryCode>US</CountryCode><PostalCode>19406</PostalCode></ShippingAddress>'
            ],
            [
                // postalCode missing
                [
                    'shipToLines' => [
                        'Street 1',
                        'Street 2',
                        'Street 3',
                        'Street 4'
                    ],
                    'shipToCity' => 'King of Prussia',
                    'shipToMainDivision' => 'PA',
                    'shipToCountryCode' => 'US',
                ],
                // skip postalCode node
                '<ShippingAddress><Line1>Street 1</Line1><Line2>Street 2</Line2><Line3>Street 3</Line3><Line4>Street 4</Line4><City>King of Prussia</City><MainDivision>PA</MainDivision><CountryCode>US</CountryCode></ShippingAddress>'
            ]
        ];
    }

    /**
     * Provide test data to verify serializeBillingAddress
     */
    public function provideBillingAddressData()
    {
        return [
            [
                // optional fields present
                [
                    'billingLines' => [
                        'Street 1',
                        'Street 2',
                        'Street 3',
                        'Street 4'
                    ],
                    'billingCity' => 'King of Prussia',
                    'billingMainDivision' => 'PA',
                    'billingCountryCode' => 'US',
                    'billingPostalCode' => '19406'
                ],
                // full section returned
                '<BillingAddress><Line1>Street 1</Line1><Line2>Street 2</Line2><Line3>Street 3</Line3><Line4>Street 4</Line4><City>King of Prussia</City><MainDivision>PA</MainDivision><CountryCode>US</CountryCode><PostalCode>19406</PostalCode></BillingAddress>'
            ],
            [
                // mainDivision missing
                [
                    'billingLines' => [
                        'Street 1',
                        'Street 2',
                        'Street 3',
                        'Street 4'
                    ],
                    'billingCity' => 'King of Prussia',
                    'billingCountryCode' => 'US',
                    'billingPostalCode' => '19406'
                ],
                // skip mainDivision node
                '<BillingAddress><Line1>Street 1</Line1><Line2>Street 2</Line2><Line3>Street 3</Line3><Line4>Street 4</Line4><City>King of Prussia</City><CountryCode>US</CountryCode><PostalCode>19406</PostalCode></BillingAddress>'
            ],
            [
                // postalCode missing
                [
                    'billingLines' => [
                        'Street 1',
                        'Street 2',
                        'Street 3',
                        'Street 4'
                    ],
                    'billingCity' => 'King of Prussia',
                    'billingMainDivision' => 'PA',
                    'billingCountryCode' => 'US',
                ],
                // skip postalCode node
                '<BillingAddress><Line1>Street 1</Line1><Line2>Street 2</Line2><Line3>Street 3</Line3><Line4>Street 4</Line4><City>King of Prussia</City><MainDivision>PA</MainDivision><CountryCode>US</CountryCode></BillingAddress>'
            ]
        ];
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

    /**
     * Inject property values into $class
     *
     * @param $class
     * @param array $properties array of property => value pairs
     */
    protected function injectProperties($class, $properties)
    {
        // use reflection to inject properties/values into the $class object
        $reflection = new \ReflectionClass($class);
        foreach ($properties as $property => $value) {
            $requestProperty = $reflection->getProperty($property);
            $requestProperty->setAccessible(true);
            $requestProperty->setValue($class, $value);
        }
    }

    /**
     * Test the cleanString utility function
     * @param $value
     * @param $maxLength
     * @param $expected
     * @dataProvider provideCleanStringTests
     */
    public function testCleanString($value, $maxLength, $expected)
    {
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanString');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($value, $maxLength));
        $this->assertSame($expected, $cleaned);
    }

    /**
     * @param $lines
     * @param $expected
     * @dataProvider provideCleanAddressLinesTests
     */
    public function testCleanAddressLines($lines, $expected)
    {
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'cleanAddressLines');
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, array($lines));
        $this->assertSame($expected, $cleaned);
    }

    /**
     * @param string $value
     * @param boolean $expected
     * @dataProvider provideBooleanFromStringTests
     */
    public function testBooleanFromString($value, $expected)
    {
        $payload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest', 'booleanFromString');
        $method->setAccessible(true);
        $actual = $method->invokeArgs($payload, array($value));
        $this->assertEquals($expected, $actual);
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
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailPayloadValidation(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload()));
        $payload->serialize();
    }

    /**
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailXsdValidation(array $payloadData)
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
     * @param array $properties
     * @param $expected
     * @dataProvider provideVerificationData
     */
    public function testSerializeVerificationDataHandlesMissingData($properties, $expected)
    {
        $request = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);// $this->getMockRequestObject();
        $this->injectProperties($request, $properties);
        $method = new \ReflectionMethod($request, 'serializeSecureVerificationData');
        $method->setAccessible(true);
        $actual = $method->invoke($request);
        $this->assertSame($expected, $actual);
    }

    /**
     * @param array $properties
     * @param $expected
     * @dataProvider provideShippingAddressData
     */
    public function testSerializeShippingAddressHandlesMissingData($properties, $expected)
    {
        $request = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $this->injectProperties($request, $properties);
        $method = new \ReflectionMethod($request, 'serializeShippingAddress');
        $method->setAccessible(true);
        $actual = $method->invoke($request);
        $this->assertSame($expected, $actual);
    }

    /**
     * @param array $properties
     * @param $expected
     * @dataProvider provideBillingAddressData
     */
    public function testSerializeBillingAddressHandlesMissingData($properties, $expected)
    {
        $request = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $this->injectProperties($request, $properties);
        $method = new \ReflectionMethod($request, 'serializeBillingAddress');
        $method->setAccessible(true);
        $actual = $method->invoke($request);
        $this->assertSame($expected, $actual);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailSchemaValidation()
    {
        $this->schemaValidatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->xmlInvalidTestString();

        $newPayload = new CreditCardAuthRequest($this->validatorIterator, $this->schemaValidatorStub);
        $newPayload->deserialize($xml);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailPayloadValidation()
    {
        $this->validatorStub->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
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
