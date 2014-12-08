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

/**
 * LineItem Test Case
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class LineItemTest extends \PHPUnit_Framework_TestCase
{
    const ROOT_NODE = ILineItem::ROOT_NODE;
    const XML_NS = ILineItem::XML_NS;

    /** @var Payload\IValidator (stub) */
    protected $stubValidator;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInValidPayload()
    {
        return [
            [[]], // Empty payload should fail validation.
            [
                [
                    // name and quantity are required
                    'setSequenceNumber' => '1',
                ]
            ],
            [
                [
                    'setName' => 'line item',
                    'setQuantity' => 1,
                    'setUnitAmount' => 2.0,
                    // 'setCurrencyCode' => 'USD', // currency code is require if amount is specified
                    'setSequenceNumber' => '1',
                ]
            ],
        ];
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideValidPayload()
    {
        return [
            [
                [
                    'setName' => 'line item',
                    'setQuantity' => 1,
                    'setUnitAmount' => 2.0,
                    'setCurrencyCode' => 'USD',
                    'setSequenceNumber' => '1',
                ],
                'LineItem.xml'
            ],
            [
                [
                    'setName' => 'line item',
                    'setQuantity' => 1,
                    // the below fields are optional
                    // 'setUnitAmount' => 2.0,
                    // 'setCurrencyCode' => 'USD',
                    // 'setSequenceNumber' => '1',
                ],
                'LineItemNoOptionalFields.xml'
            ],
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
     * Create a payload with the provided data.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return LineItem
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();

        foreach ($properties as $setterMethod => $value) {
            $payload->$setterMethod($value);
        }
        return $payload;
    }

    /**
     * Get a new LineItem payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return LineItem
     */
    protected function createNewPayload()
    {
        return new LineItem($this->validatorIterator);
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
        $this->stubValidator->expects($this->once())
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
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData, $xml)
    {
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $expectedPayload = $this->buildPayload($payloadData);
        $payload = $this->buildPayload([]);

        // inject the xml namespace into the string as the deserialize expects
        // the elements to be in a specific namespace.
        $serializedPayload = '<' . self::ROOT_NODE . ' xmlns="' . self::XML_NS . '">'
            . substr($this->xmlTestString($xml), strlen('<LineItem>'));
        $payload->deserialize($serializedPayload);
        $this->assertEquals($expectedPayload, $payload);
    }

    /**
     * verify a set of known data will serialize as expected
     * @param  array  $payloadData
     * @param  string $xml
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData, $xml)
    {
        $payload = $this->buildPayload($payloadData);
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $domPayload = new DOMDocument();
        $domPayload->preserveWhiteSpace = false;
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();
        $domPayload->loadXML($this->xmlTestString($xml));
        $expectedString = $domPayload->C14N();
        $this->assertEquals($expectedString, $serializedString);
    }

    /**
     * Read an XML file with valid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlTestString($xml)
    {
        $dom = new DOMDocument();
        $dom->load(__DIR__ . "/Fixtures/$xml");
        $string = $dom->C14N();

        return $string;
    }
}
