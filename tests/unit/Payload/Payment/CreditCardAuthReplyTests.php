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

class CreditCardAuthReplyTests extends \PHPUnit_Framework_TestCase
{
    public function provideInvalidPayload()
    {
        $payload = new CreditCardAuthReply();

        return array(
            array($payload)
        );
    }

    public function provideValidPayload()
    {
        // move to JSON
        $properties = array(
            'orderId' => 'ORDER_ID',
            'paymentAccountUniqueId' => '4111ABC123ZYX987',
            'panIsToken' => true,
            'authorizationResponseCode' => 'AP01',
            'bankAuthorizationCode' => 'OK',
            'cvv2ResponseCode' => 'M',
            'avsResponseCode' => 'M',
            'amountAuthorized' => 55.99,
            'currencyCode' => 'USD',
        );

        return array(
            array($this->buildPayload($properties))
        );
    }

    protected function buildPayload($properties)
    {
        $payload = new CreditCardAuthReply();
        $payloadReflection = new \ReflectionClass($payload);
        foreach ($properties as $property => $value) {
            if ($payloadReflection->hasProperty($property)) {
                $property = $payloadReflection->getProperty($property);
                $property->setAccessible(true);
                $property->setValue($payload, $value);
            }
        }
        return $payload;
    }

    protected function loadXmlTestString()
    {
        $dom = new \DOMDocument();
        $dom->loadXML('./Fixtures/CreditCardAuthReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    protected function loadXmlInvalidTestString()
    {
        $dom = new \DOMDocument();
        $dom->loadXML('./Fixtures/InvalidCreditCardAuthReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * @param ICreditCardAuthReply $payload
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testValidateWillFail(ICreditCardAuthReply $payload)
    {
        $payload->validate();
    }

    /**
     * @param ICreditCardAuthReply $payload
     * @dataProvider provideValidPayload
     */
    public function testValidateWillPass(ICreditCardAuthReply $payload)
    {
        $this->assertSame($payload, $payload->validate());
    }

    /**
     * @param ICreditCardAuthReply $payload
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFail(ICreditCardAuthReply $payload)
    {
        $payload->serialize();
    }

    /**
     * @param ICreditCardAuthReply $payload
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(ICreditCardAuthReply $payload)
    {
        $domPayload = new \DOMDocument();
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();

        $this->assertEquals($this->loadXmlTestString(), $serializedString);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFail()
    {
        $xml = $this->loadXmlInvalidTestString();

        $newPayload = new CreditCardAuthReply();
        $newPayload->deserialize($xml);
    }

    /**
     * @param ICreditCardAuthReply $payload
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(ICreditCardAuthReply $payload)
    {
        $xml = $this->loadXmlTestString();

        $newPayload = new CreditCardAuthReply();
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }
}
