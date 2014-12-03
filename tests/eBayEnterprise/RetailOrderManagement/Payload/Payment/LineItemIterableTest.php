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
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LineItemIterableTest extends \PHPUnit_Framework_TestCase
{
    /** @var Payload\IValidator (stub) */
    protected $stubValidator;
    /** @var Payload\ISchemaValidator (stub) */
    protected $stubSchemaValidator;
    /** @var Payload\Payment\ILineItem (stub) */
    protected $stubLineItem;
    /** @var Payload\IPayloadMap (stub) */
    protected $stubPayloadMap;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->stubPayloadMap = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap');
        $this->stubLineItem = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem');
        $this->stubLineItem->expects($this->any())
            ->method('serialize')->will($this->returnValue('<LineItem></LineItem>'));
        $this->stubLineItem->expects($this->any())
            ->method('deserialize')->will($this->returnSelf());
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInValidPayload()
    {
        return [
            [
                [
                    'setLineItemsTotal' => '',
                    'setShippingTotal' => '',
                    'setCurrencyCode' => '',
                    'setTaxTotal' => '',
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
                    'setLineItemsTotal' => 0.00,
                    'setShippingTotal' => 0.00,
                    'setTaxTotal' => 0.00,
                    'setCurrencyCode' => 'USD',
                ],
                'LineItemIterableEmpty.xml'
            ],
            [
                [
                    'setLineItemsTotal' => 2.00,
                    'setShippingTotal' => 0.50,
                    'setTaxTotal' => 0.25,
                    'setCurrencyCode' => 'USD',
                    'ADDITEM' => 1,
                ],
                'LineItemIterable.xml'
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
        $this->stubValidator->expects($this->atLeastOnce())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload->validate();
    }

    /**
     * Create a payload with the provided data.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return LineItemIterable
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();

        foreach ($properties as $setterMethod => $value) {
            if ($setterMethod === 'ADDITEM') {
                $payload->attach($this->stubLineItem);
            } else {
                $payload->$setterMethod($value);
            }
        }
        return $payload;
    }

    /**
     * Get a new LineItemIterable payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return LineItemIterable
     */
    protected function createNewPayload()
    {
        return new LineItemIterable($this->validatorIterator, $this->stubSchemaValidator, $this->stubPayloadMap);
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
        $this->stubValidator->expects($this->once())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload = $this->buildPayload($payloadData);
        $payload->serialize();
    }

    /**
     * @param array $payloadData
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

    /**
     * @param array $payloadData mapping of setter name to value
     * @param string $xml name of fixture file to use
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData, $xml)
    {
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->returnSelf());
        $expectedPayload = $this->buildPayload($payloadData);
        /** @var Payload\Payment\LineItemIterable $payload */
        $payload = $this->getMockBuilder('\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable')
            ->setConstructorArgs([$this->validatorIterator, $this->stubSchemaValidator, $this->stubPayloadMap])
            ->setMethods(['getEmptyLineItem'])
            ->getMock();
        $payload->expects($this->any())
            ->method('getEmptyLineItem')->will($this->returnValue($this->stubLineItem));
        // inject the xml namespace into the string as the deserialize expects
        // the elements to be in a specific namespace.
        $serializedPayload = '<' . ILineItemIterable::ROOT_NODE . ' xmlns="' . ILineItemIterable::XML_NS . '">'
            . substr($this->xmlTestString($xml), strlen('<' . ILineItemIterable::ROOT_NODE . '>'));
        $payload->deserialize($serializedPayload);

        $this->assertSame($expectedPayload->count(), $payload->count(), 'payloads differ in number of contained items');
        foreach ($expectedPayload as $expectedLineItem) {
            $this->assertTrue($payload->offsetExists($expectedLineItem));
        }
        $this->assertEquals($expectedPayload, $payload);
    }

    /**
     * ensure LineItem object is returned
     */
    public function testGetEmptyLineItem()
    {
        $payloadMap = new Payload\PayloadMap([
            LineItemIterable::LINE_ITEM_INTERFACE => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItem'
        ]);
        $payload = new LineItemIterable($this->validatorIterator, $this->stubSchemaValidator, $payloadMap);
        $lineItem = $payload->getEmptyLineItem();
        $this->assertInstanceOf('\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItem', $lineItem);
    }

    /**
     * verify calculateLineItemsTotal sets the correct value.
     * @param array $payloadData mapping of setter name to value
     * @dataProvider provideValidPayload
     */
    public function testCalcualteLineItemsTotal(array $payloadData)
    {
        $this->stubLineItem->expects($this->any())
            ->method('getQuantity')->will($this->returnValue(1));
        $this->stubLineItem->expects($this->any())
            ->method('getUnitAmount')->will($this->returnValue(2.0));

        $expected = $payloadData['setLineItemsTotal'];
        unset($payloadData['setLineItemsTotal']);
        $payload = $this->buildPayload($payloadData);
        $this->assertNull($payload->getLineItemsTotal());
        // the method returns the payload.
        $this->assertSame($payload, $payload->calculateLineItemsTotal());
        $this->assertSame($expected, $payload->getLineItemsTotal());
    }
}
