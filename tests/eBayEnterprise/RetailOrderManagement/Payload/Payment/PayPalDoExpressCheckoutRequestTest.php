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

class PayPalDoExpressCheckoutRequestTest extends \PHPUnit_Framework_TestCase
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
    /** @var Payload\Payment\ILineItem (stub) */
    protected $stubLineItemA;
    /** @var Payload\Payment\ILineItem (stub) */
    protected $stubLineItemB;
    /** @var Payload\Payment\ILineItemIterable */
    protected $lineItemIterable;

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
     * Provide test data to verify serializeShippingAddress
     */
    public function provideShippingAddressData()
    {
        return [[
            [
                'setOrderId' => '1234567',
                'setRequestId' => '1234567890',
                'setToken' => 'EC-5YE59312K56892714',
                'setPayerId' => 'PayerId0',
                'setAmount' => 50.00,
                'setPickUpStoreId' => '', // should not be serialized
                'setShipToName' => 'John Smith',
                'setCurrencyCode' => 'USD',
                'setShipToLines' => "this is line1\n\n630 Allendale Rd\n2nd FL",
                'setShipToCity' => 'Anytown',
                'setShipToMainDivision' => 'ST',
                'setShipToCountryCode' => 'US',
                'setShipToPostalCode' => '11199'
            ], 'PayPalDoExpressCheckoutRequest.xml'
        ]];
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
     * @return PayPalDoExpressCheckoutRequest
     */
    protected function buildPayload(array $properties)
    {
        $payload = new PayPalDoExpressCheckoutRequest(
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

    /**
     * setup necessary methods on an mock ILineItem
     * @param  ILineItem $item
     * @param  string    $name
     * @param  int       $qty
     * @param  float     $unitAmount
     */
    protected function setupLineItemStub(ILineItem $item, $name, $qty, $unitAmount)
    {
        $productXml = "<LineItem><Name>$name</Name></LineItem>";
        $item->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue($productXml));
        $item->expects($this->any())
            ->method('getQuantity')
            ->will($this->returnValue($qty));
        $item->expects($this->any())
            ->method('getUnitAmount')
            ->will($this->returnValue($unitAmount));
    }

    /**
     * verify a set of known data will serialize as expected
     * @param  array  $payloadData
     * @param  string $xml
     * @dataProvider provideShippingAddressData
     */
    public function testSerializeWillPass(array $payloadData, $xml)
    {
        $this->setupLineItemStub($this->stubLineItemA, 'Product A', 1, 50.0);
        $this->setupLineItemStub($this->stubLineItemB, 'Product B', 1, 50.0);

        $this->lineItemIterable->setShippingTotal(10);
        $this->lineItemIterable->setTaxTotal(5);
        $this->lineItemIterable->setCurrencyCode('USD');
        $this->lineItemIterable->calculateLineItemsTotal();

        $payload = $this->buildPayload($payloadData);
        $payload->setLineItems($this->lineItemIterable);

        $domPayload = new DOMDocument();
        $domPayload->preserveWhiteSpace = false;
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();
        $domPayload->loadXML($this->xmlTestString($xml));
        $expectedString = $domPayload->C14N();
        $this->assertEquals($expectedString, $serializedString);
    }

    protected function setUp()
    {
        $this->payloadMapStub = $this->stubPayloadMap();
        $this->validatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->validatorStub]);
        $this->schemaValidatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->stubLineItemA = $this->getMock(
            '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem'
        );
        $this->stubLineItemB = $this->getMock(
            '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItem'
        );
        $this->lineItemIterable = new LineItemIterable(
            $this->validatorIterator,
            $this->schemaValidatorStub,
            $this->payloadMapStub
        );
        $this->lineItemIterable[$this->stubLineItemA] = null;
        $this->lineItemIterable[$this->stubLineItemB] = null;
    }

    /**
     * Read an XML file with valid payload data and return a canonicalized string
     *
     * @return string
     */
    protected function xmlTestString()
    {
        $dom = new DOMDocument();
        $dom->load(__DIR__ . '/Fixtures/PayPalDoExpressCheckoutRequest.xml');
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
        $reflection = new ReflectionClass($class);
        foreach ($properties as $property => $value) {
            $requestProperty = $reflection->getProperty($property);
            $requestProperty->setAccessible(true);
            $requestProperty->setValue($class, $value);
        }
    }
}
