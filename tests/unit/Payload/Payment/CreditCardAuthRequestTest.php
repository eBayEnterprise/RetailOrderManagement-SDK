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

class CreditCardAuthRequestTest extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidPayload()
    {
        $payload = new CreditCardAuthRequest();

        return array(
            array($payload)
        );
    }

    public function provideValidPayload()
    {
        // move to JSON
        $properties = array(
            'setRequestId' => 'testReqId',
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
            'setBillingPhone' => '1234567890',
            'setBillingLines' => 'Street 1\nStreet 2\nStreet 3\nStreet4',
            'setBillingCity' => 'King of Prussia',
            'setBillingMainDivision' => 'PA',
            'setBillingCountryCode' => 'US',
            'setBillingPostalCode' => '19406',
            'setShipToFirstName' => 'First',
            'setShipToLastName' => 'Last',
            'setShipToPhone' => '123-456-7890',
            'setShipToLines' => 'Street 1\nStreet 2\nStreet 3\nStreet4',
            'setShipToCity' => 'City',
            'setShipToMainDivision' => 'PA',
            'setShipToCountryCode' => 'US',
            'setShipToPostalCode' => '12345',
            'setIsRequestToCorrectCvvOrAvsError' => false,
            'setAuthenticationAvailable' => 'Y',
            'setAuthenticationStatus' => 'Y',
            'setCavvUcaf' => 'abcd1234',
            'setTransactionId' => 'transId',
            'setEci' => 'ECI',
            'setPayerAuthenticationResponse' => 'some REALLY big string'
        );

        return array(
            array($this->buildPayload($properties))
        );
    }

    protected function buildPayload($properties)
    {
        $payload = new CreditCardAuthRequest();

        foreach ($properties as $property => $value) {
            $payload->$property($value);
        }

        return $payload;
    }

    protected function xmlTestString()
    {
        $dom = \DOMDocument::loadXML('./Fixtures/CreditCardAuthRequest.xml');
        $string = $dom->C14N();

        return $string;
    }

    protected function xmlInvalidTestString()
    {
        $dom = \DOMDocument::loadXML('./Fixtures/InvalidCreditCardAuthRequest.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * @param ICreditCardAuthRequest $payload
     * @dataProvider provideInvalidPayload
     * @expectedException eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testValidateWillFail(ICreditCardAuthRequest $payload)
    {
        $payload->validate();
    }

    /**
     * @param ICreditCardAuthRequest $payload
     * @dataProvider provideValidPayload
     */
    public function testValidateWillPass(ICreditCardAuthRequest $payload)
    {
        $this->assertSame($payload, $payload->validate());
    }

    /**
     * @param ICreditCardAuthRequest $payload
     * @dataProvider provideInvalidPayload
     * @expectedException eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFail(ICreditCardAuthRequest $payload)
    {
        $payload->serialize();
    }

    /**
     * @param ICreditCardAuthRequest $payload
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(ICreditCardAuthRequest $payload)
    {
        $domPayload = \DOMDocument::loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();

        $this->assertEquals($this->xmlTestString(), $serializedString);
    }

    /**
     * @expectedException eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFail()
    {
        $xml = $this->xmlInvalidTestString();

        $newPayload = new CreditCardAuthRequest();
        $newPayload->deserialize($xml);
    }

    /**
     * @param ICreditCardAuthRequest $payload
     * @dataProvider provideXML
     */
    public function testDeserializeWillPass(ICreditCardAuthRequest $payload)
    {
        $xml = $this->xmlTestString();

        $newPayload = new CreditCardAuthRequest();
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }
}
