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

/**
 * Test the paypal address class
 */
class PayPalAddressTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Payload\IValidator */
    protected $stubValidator;
    /** @var Payload\ISchemaValidator */
    protected $stubSchemaValidator;
    /** @var Payload\ValidatorIterator */
    protected $validatorIterator;

    public function setUp()
    {
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
    }

    /**
     * data provider of empty array of properties
     * Empty properties will generate an invalid IPayload object
     *
     * @return array $payloadData
     */
    public function provideInvalidPayload()
    {
        $emptyPayload = [];
        $missingLine1 = [
            'setAddressStatus' => 'Confirmed' ,
            'setMainDivision' => 'PA',
            'setPostalCode' => '19406',
        ];

        return [
            [$emptyPayload],
            [$missingLine1],
        ];
    }
    public function provideValidPayload()
    {
        return [
            [[
                'setLines' => "Street 1\nStreet 2\n Street 3\nStreet 4",
                'setCity' => 'King of Prussia',
                'setMainDivision' => 'PA',
                'setCountryCode' => 'US',
                'setPostalCode' => '19406',
                'setAddressStatus' => 'Confirmed' ,
            ], 'full'],
            [[
                'setLines' => "Street 1",
                'setCity' => 'King of Prussia',
                'setCountryCode' => 'US',
            ], 'minimal'],
        ];
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
     * verify the payload deserializes properly
     * @param array  $payloadData
     * @param string $case
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData, $case)
    {
        $expectedPayload = $this->buildPayload($payloadData);
        $payload = $this->buildPayload([]);
        $payload->deserialize($this->loadXmlTestString($case));
        $this->assertEquals($expectedPayload, $payload);
    }
    /**
     * verify the payload serializes properly
     * @param array  $payloadData
     * @param string $case
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData, $case)
    {
        $expectedDoc = new \DOMDocument();
        $expectedDoc->preserveWhiteSpace = false;
        $expectedDoc->loadXML($this->loadXmlTestString($case));

        $payload = $this->buildPayload($payloadData);
        $domPayload = new \DOMDocument();
        $domPayload->preserveWhiteSpace = false;
        $domPayload->loadXML('<AddressElement>'. $payload->serialize() . '</AddressElement>');

        $this->assertEquals($expectedDoc->C14N(), $domPayload->C14N());
    }
    /**
     * Create a payload with the provided data.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return PayPalGetExpressCheckoutReply
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();
        foreach ($properties as $property => $value) {
            $payload->$property($value);
        }
        return $payload;
    }
    /**
     * read in an xml string from the fixtures
     * @param  string $case
     * @return string
     */
    protected function loadXmlTestString($case)
    {
        $contents = file_get_contents(__DIR__ . '/Fixtures/PayPalAddressTest.xml');
        $caseStart = strpos($contents, "<!-- $case -->\n") + strlen("<!-- $case -->\n");
        $caseEnd = strpos($contents, "<!-- ", $caseStart + 1);
        return ($caseEnd === false) ?
            substr($contents, $caseStart) :
            substr($contents, $caseStart, $caseEnd - $caseStart);
    }
    /**
     * Get a new PayPalAddress payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return PayPalAddress
     */
    protected function createNewPayload()
    {
        return new PayPalAddress($this->validatorIterator, $this->stubSchemaValidator);
    }
}
