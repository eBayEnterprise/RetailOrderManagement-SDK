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

class CreditCardAuthReplyTest extends \PHPUnit_Framework_TestCase
{
    /** @var Payload\IValidator (stub) **/
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
        $this->validatorIterator = new Payload\ValidatorIterator(array($this->stubValidator));
    }

    /**
     * Get a new CreditCardAuthReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return CreditCardAuthReply
     */
    protected function createNewPayload()
    {
        return new CreditCardAuthReply($this->validatorIterator);
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInvalidPayload()
    {
        $payloadData = array();

        return array(
            array($payloadData)
        );
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
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
            'phoneResponseCode' => 'PHONE_OK',
            'nameResponseCode' => 'NAME_OK',
        );

        return array(
            array($properties)
        );
    }

    /**
     * Create a payload with the provided data injected.
     * @param  mixed[] $properties key/value pairs of property => value
     * @return CreditCardAuthReply
     */
    protected function buildPayload($properties)
    {
        $payload = $this->createNewPayload();
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

    /**
     * Load the XML from a fixture file and canonicalize it. Returns the
     * canonical XML string.
     * @return string
     */
    protected function loadXmlTestString()
    {
        return file_get_contents(__DIR__ . '/Fixtures/CreditCardAuthReply.xml');
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function loadXmlInvalidTestString()
    {
        return file_get_contents(__DIR__ . '/Fixtures/InvalidCreditCardAuthReply.xml');
    }

    /**
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
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFail(array $payloadData)
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
    public function testSerializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
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
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->loadXmlTestString();

        $newPayload = new CreditCardAuthReply();
        $newPayload->deserialize($xml);

        $this->assertEquals($payload, $newPayload);
    }
    /**
     * Check for the authorization success to be false when errors were
     * returned in the reply.
     * @param  array  $payloadData
     * @dataProvider provideUnsuccessfulPayload
     */
    public function testGetIsAuthSuccessfulPayloadWithErrors(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertFalse($payload->getIsAuthSuccessful());
    }
    /**
     * Check for the authorization successful to be true when there were no
     * errors returned in the reply.
     * @param  array  $payloadData
     * @dataProvider provideSuccessfulPayload
     */
    public function testGetIsAuthSuccessfulPayloadNoErrors(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertTrue($payload->getIsAuthSuccessful());
    }
    /**
     * Check for the authorization to be unacceptable if the reply contains
     * any error.
     * @param  array  $payloadData
     * @dataProvider provideUnacceptableAuthPayload
     */
    public function testGetIsAuthAcceptableUnacceptablePayload(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertFalse($payload->getIsAuthAcceptable());
    }
    /**
     * Check for the authorization to be acceptable if the reply is successful
     * or reports a timeout.
     * @param  array  $payloadData
     * @dataProvider provideAcceptableAuthPayload
     */
    public function testGetIsAuthAcceptableAcceptablePayload(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertTrue($payload->getIsAuthAcceptable());
    }
    /**
     * Check for the payload response code to match the expected response
     * code. Response code should be 'APPROVED' for acceptable authorizations,
     * 'TIMEOUT' for requests that indicate a timeout or null when the authorization
     * reply should not be accepted.
     * @param  array  $payloadData
     * @param  string|null $responseCode
     * @dataProvider provideResponseCodePayloadAndCode
     */
    public function testGetReplyResponseCode(array $payloadData, $responseCode)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertSame($responseCode, $payload->getResponseCode());
    }
}
