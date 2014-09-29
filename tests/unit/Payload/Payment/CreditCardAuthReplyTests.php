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

use eBayEnterprise\RetailOrderManagement\Payload\Exception;

class CreditCardAuthReplyTests extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidPayload()
    {
        $payload = new Payment\ICreditCardAuthReply();

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
            'setExpirationDate' => date_create('2015-12'),
            'setCardSecurityCode' => '123',
            'setAmount' => 43.45,
            'setCurrencyCode' => 'USD',
            'setEmail' => 'test@example.com',
            'setIp' => '127.0.0.1',
            'setBillingFirstName' => 'First',
            'setBillingLastName' => 'Last',
            'setBillingPhone' => '1234567890',
            'setBillingLine1' => 'Street 1',
            'setBillingLine2' => 'Street 2',
            'setBillingLine3' => 'Street 3',
            'setBillingLine4' => 'Street 4',
            'setBillingCity' => 'King of Prussia',
            'setBillingMainDivision' => 'PA',
            'setBillingCountryCode' => 'US',
            'setBillingPostalCode' => '19406',
            'setShipToFirstName' => 'First',
            'setShipToLastName' => 'Last',
            'setShipToPhone' => 'Street 1',
            'setShipToLine1' => 'Street 2',
            'setShipToLine2' => 'Street 3',
            'setShipToLine3' => 'Street 4',
            'setShipToLine4' => 'Street 5',
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
        $payload = new Payment\ICreditCardAuthReply();

        foreach ($properties as $property => $value) {
            $payload->$property($value);
        }

        return $payload;
    }

    protected function xmlTestString()
    {
        $dom = DOMDocument::loadXML('./Fixtures/CreditCardAuthReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    protected function xmlInvalidTestString()
    {
        $dom = DOMDocument::loadXML('./Fixtures/InvalidCreditCardAuthReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * @param Payment\ICreditCardAuthReply $payload
     * @dataProvider provideInvalidPayload
     * @expectedException Exception\InvalidPayload
     */
    public function testValidateWillFail(Payment\ICreditCardAuthReply $payload)
    {
        $payload->validate();
    }

    /**
     * @param Payment\ICreditCardAuthReply $payload
     * @dataProvider provideValidPayload
     */
    public function testValidateWillPass(Payment\ICreditCardAuthReply $payload)
    {
        $this->assertSame($payload, $payload->validate());
    }

    /**
     * @param Payment\ICreditCardAuthReply $payload
     * @dataProvider provideInvalidPayload
     * @expectedException Exception\InvalidPayload
     */
    public function testSerializeWillFail(Payment\ICreditCardAuthReply $payload)
    {
        $payload->serialize();
    }

    /**
     * @param Payment\ICreditCardAuthReply $payload
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(Payment\ICreditCardAuthReply $payload)
    {
        $domPayload = DOMDocument::loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();

        $this->assertEquals($this->xmlTestString(), $serializedString);
    }

    /**
     * @expectedException Exception\InvalidPayload
     */
    public function testDeserializeWillFail()
    {
        $xml = $this->xmlInvalidTestString();

        $newPayload = new Payment\ICreditCardAuthReply();
        $newPayload->deserialize($xml);
    }

    /**
     * @param Payment\ICreditCardAuthReply $payload
     * @dataProvider provideXML
     */
    public function testDeserializeWillPass(Payment\ICreditCardAuthReply $payload)
    {
        $xml = $this->xmlTestString();

        $newPayload = new Payment\ICreditCardAuthReply();
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }
} 