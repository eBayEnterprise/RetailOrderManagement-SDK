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

class StoredValueBalanceRequestTest extends \PHPUnit_Framework_TestCase
{
    /** @var  Payload\IValidator */
    protected $validatorStub;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var  Payload\ISchemaValidator */
    protected $schemaValidatorStub;

    /**
     * data provider to provide an empty array of properties
     * Empty properties will generate and invalid IPayload object
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
     * Data provider to provide an array of valid property values that will generate an valid IPayload object
     *
     * @return array $payloadData
     */
    public function provideValidPayload()
    {
        // move to JSON
        $properties = [
            'setPanIsToken' => true,
            'setCardNumber' => 'KDVXYXCeFCG8GfH6',
            'setCurrencyCode' => 'GBP',
            'setPin' => '1234',
        ];
        $noPin = [
            'setPanIsToken' => true,
            'setCardNumber' => 'KDVXYXCeFCG8GfH6',
            'setCurrencyCode' => 'GBP',
        ];

        return [
            [$properties, ''],
            [$noPin, 'NoPin'],
        ];
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
     * @return StoredValueBalanceRequest
     */
    protected function buildPayload(array $properties)
    {
        $payload = new StoredValueBalanceRequest($this->validatorIterator, $this->schemaValidatorStub);

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
     * @param string $case
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData, $case)
    {
        $payload = $this->buildPayload($payloadData);
        $this->schemaValidatorStub->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $domPayload = new DOMDocument();
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();

        $this->assertEquals($this->xmlTestString($case), $serializedString);
    }

    /**
     * Read an XML file with valid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlTestString($case)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->load(__DIR__ . "/Fixtures/StoredValueBalanceRequest{$case}.xml");
        $string = $dom->C14N();

        return $string;
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

        $newPayload = new StoredValueBalanceRequest($this->validatorIterator, $this->schemaValidatorStub);
        $newPayload->deserialize($xml);
    }

    /**
     * Read an XML file with invalid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlInvalidTestString()
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->load(__DIR__ . '/Fixtures/InvalidStoredValueBalanceRequest.xml');
        $string = $dom->C14N();

        return $string;
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

        $newPayload = new StoredValueBalanceRequest($this->validatorIterator, $this->schemaValidatorStub);
        $newPayload->deserialize($xml);
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData, $case)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->xmlTestString($case);
        $this->assertNotEmpty($xml);
        $newPayload = new StoredValueBalanceRequest($this->validatorIterator, $this->schemaValidatorStub);
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }

    protected function setUp()
    {
        $this->validatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->validatorStub]);
        $this->schemaValidatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
    }
}
