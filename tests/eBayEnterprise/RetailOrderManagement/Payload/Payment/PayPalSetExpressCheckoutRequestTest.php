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
use eBayEnterprise\RetailOrderManagement\Util\TPayloadTest;
use ReflectionClass;
use ReflectionMethod;

class PayPalSetExpressCheckoutRequestTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    /** @var  Payload\IValidator */
    protected $validatorStub;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var  Payload\ISchemaValidator */
    protected $schemaValidatorStub;
    /** @var Payload\IPayloadMap (stub) */
    protected $payloadMapStub;

    /**
     * data provider of empty array of properties
     * Empty properties will generate an invalid IPayload object
     *
     * @return array $payloadData
     */
    public function provideInvalidPayload()
    {
        $payloadData = [];

        return [
            [$payloadData]
        ];
    }

    /**
     * Provide test data for the cleanAddressLines function.
     * @return array
     */
    public function provideCleanAddressLinesTests()
    {
        return [
            [  // good data
                "Street 1\nStreet 2\nStreet 3\nStreet 4",
                ['Street 1', 'Street 2', 'Street 3', 'Street 4'],
            ],
            [  // extra lines
                "Street 1\n"
                . "Street 2\n"
                . " Street 3\n"
                . "Street 4\n\n\n\n\n"
                . str_repeat('.', 100),
                ['Street 1', 'Street 2', 'Street 3', 'Street 4 ' . str_repeat('.', 61)],
            ],
            [ // not a string
                100,
                null
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
                [
                    'shipToLines' => ['Chester Cheetah', '630 Allendale Rd', '2nd FL'],
                    'shipToCity' => 'King of Prussia',
                    'shipToMainDivision' => 'PA',
                    'shipToCountryCode' => 'US',
                    'shipToPostalCode' => '19406'
                ],
                // full section returned
                '<ShippingAddress>'
                . '<Line1>Chester Cheetah</Line1>'
                . '<Line2>630 Allendale Rd</Line2>'
                . '<Line3>2nd FL</Line3>'
                . '<City>King of Prussia</City>'
                . '<MainDivision>PA</MainDivision>'
                . '<CountryCode>US</CountryCode>'
                . '<PostalCode>19406</PostalCode>'
                . '</ShippingAddress>'
            ]
        ];
    }

    /**
     * @param $lines
     * @param $expected
     * @dataProvider provideCleanAddressLinesTests
     */
    public function testCleanAddressLines($lines, $expected)
    {
        $payload = new PayPalSetExpressCheckoutRequest(
            $this->validatorIterator,
            $this->schemaValidatorStub,
            $this->payloadMapStub
        );
        $method = new ReflectionMethod(
            '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutRequest',
            'cleanAddressLines'
        );
        $method->setAccessible(true);
        $cleaned = $method->invokeArgs($payload, [$lines]);
        $this->assertSame($expected, $cleaned);
    }

    /**
     * @param array $properties
     * @param $expected
     * @dataProvider provideShippingAddressData
     */
    public function testSerializeShippingAddress($properties, $expected)
    {
        $payload = new PayPalSetExpressCheckoutRequest(
            $this->validatorIterator,
            $this->schemaValidatorStub,
            $this->payloadMapStub
        );
        $this->injectProperties($payload, $properties);
        $method = new ReflectionMethod($payload, 'serializeShippingAddress');
        $method->setAccessible(true);
        $actual = $method->invoke($payload);
        $this->assertSame($expected, $actual);
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
        $reflection = new ReflectionClass($class);
        foreach ($properties as $property => $value) {
            $requestProperty = $reflection->getProperty($property);
            $requestProperty->setAccessible(true);
            $requestProperty->setValue($class, $value);
        }
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
     * Take an array of property values with property names as keys and return an IPayload object
     *
     * @param array $properties
     * @return PayPalSetExpressCheckoutRequest
     */
    protected function buildPayload(array $properties)
    {
        $payload = new PayPalSetExpressCheckoutRequest(
            $this->validatorIterator,
            $this->schemaValidatorStub,
            $this->payloadMapStub
        );
        foreach ($properties as $property => $value) {
            $payload->$property($value);
        }
        return $payload;
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

    protected function setUp()
    {
        $this->payloadMapStub = $this->stubPayloadMap();
        $this->schemaValidatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->validatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->validatorStub]);
    }

    /**
     * Read an XML file with valid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlTestString()
    {
        $dom = new DOMDocument();
        $dom->load(__DIR__ . '/Fixtures/PayPalSetExpressCheckoutRequest.xml');
        $string = $dom->C14N();

        return $string;
    }
}
