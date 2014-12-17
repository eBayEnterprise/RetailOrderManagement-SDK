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
    use Payload\TTopLevelPayloadTest;

    /** @var Payload\Payment\ILineItem (stub) */
    protected $stubLineItemA;
    /** @var Payload\IPayloadMap (stub) */
    protected $payloadMapStub;
    /** @var Payload\Payment\ILineItemIterable (stub) */
    protected $lineItemIterableStub;

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
    public function createNewPayload()
    {
        return $payload = new PayPalSetExpressCheckoutRequest(
            $this->validatorIterator,
            $this->schemaValidatorStub,
            $this->payloadMapStub,
            $this->lineItemIterableStub
        );
    }
    protected function setUp()
    {
        $this->payloadMapStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap');
        $this->schemaValidatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->validatorStub = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->validatorStub]);
        $this->lineItemIterableStub = $this->getMock(
            '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable'
        );
        $this->lineItemIterableStub->expects($this->any())
            ->method('serialize')
            ->will($this->returnValue(''));
        $this->lineItemIterableStub->expects($this->any())
            ->method('count')
            ->will($this->returnValue(0));
        $this->fullPayload = $this->buildPayload([
            'setOrderId' => '1234567',
            'setReturnUrl' => 'http://mysite.com/checkout/return.html',
            'setCancelUrl' => 'http://mysite.com/checkout/cancel.html',
            'setLocaleCode' => 'en_US',
            'setAmount' => 50.00,
            'setCurrencyCode' => 'USD',
            'setShipToLines' => "123 Main St\n",
            'setShipToCity' => 'Philadelphia',
            'setShipToMainDivision' => 'PA',
            'setShipToCountryCode' => 'US',
            'setShipToPostalCode' => '19019'
        ]);
    }

    /**
     * get the file path for the fixture file
     * @return string
     */
    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/PayPalSetExpressCheckoutRequest.xml';
    }
}
