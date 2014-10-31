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
    /** @var Payload\IValidator (stub) */
    protected $stubValidator;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var Payload\ISchemaValidator (stub) */
    protected $stubSchemaValidator;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
    }

    /**
     * Get a new CreditCardAuthReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return CreditCardAuthReply
     */
    protected function createNewPayload()
    {
        return new CreditCardAuthReply($this->validatorIterator, $this->stubSchemaValidator);
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInvalidPayload()
    {
        return [
            [[]],
            [[
                // order id should fail XSD validation
                'orderId' => '1234567890123456789012345',
                'cardNumber' => '4111ABC123ZYX987',
                'panIsToken' => true,
                'authorizationResponseCode' => 'AP01',
                'bankAuthorizationCode' => 'OK',
                'cvv2ResponseCode' => 'M',
                'avsResponseCode' => 'M',
                'amountAuthorized' => 55.99,
                'currencyCode' => 'USD',
                'phoneResponseCode' => 'PHONE_OK',
                'nameResponseCode' => 'NAME_OK',
            ]],
        ];
    }

    /**
     * Data provider for valid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideValidPayload()
    {
        // move to JSON
        $properties = [
            'orderId' => 'ORDER_ID',
            'cardNumber' => '4111ABC123ZYX987',
            'panIsToken' => true,
            'authorizationResponseCode' => 'AP01',
            'bankAuthorizationCode' => 'OK',
            'cvv2ResponseCode' => 'M',
            'avsResponseCode' => 'M',
            'amountAuthorized' => 55.99,
            'currencyCode' => 'USD',
            'phoneResponseCode' => 'PHONE_OK',
            'nameResponseCode' => 'NAME_OK',
        ];

        $encryptionProperties = [
            'isEncrypted' => true,
            // pan will be encrypted, so not a token
            'panIsToken' => false
        ];
        return [
            [$properties, 'UnencryptedCardData'],
            [array_merge($properties, $encryptionProperties), 'EncryptedCardData']
        ];
    }

    /**
     * Provide payloads of data that should result in an unsuccessful payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideUnsuccessfulPayload()
    {
        return [
            // any authorization response code other than AP01, TO01 or NR01 is
            // unsuccessful, regardless of any other responses
            [[
                'authorizationResponseCode' => 'ND01',
            ]],
            // when auth response code is successful, AVS and CVV response
            // codes must be successful as well, when they are not, reply is unsuccessful
            [[
                'authorizationResponseCode' => 'AP01',
                'cvv2ResponseCode' => 'M',
                'avsResponseCode' => 'N',
            ]],
            [[
                'authorizationResponseCode' => 'AP01',
                'cvv2ResponseCode' => 'M',
                'avsResponseCode' => 'AW',
            ]],
            [[
                'authorizationResponseCode' => 'AP01',
                'cvv2ResponseCode' => 'N',
                'avsResponseCode' => 'P',
            ]],
        ];
    }

    /**
     * Provide payloads of data that should result in a successful payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideSuccessfulPayload()
    {
        return [
            [[
                'authorizationResponseCode' => 'AP01',
                'cvv2ResponseCode' => 'M',
                'avsResponseCode' => 'P'
            ]],
        ];
    }

    /**
     * Provide payloads of data that should result in an unacceptable payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideUnacceptableAuthPayload()
    {
        return [
            [['authorizationResponseCode' => 'ND01']],
        ];
    }

    /**
     * Provide payloads of data that should result in an acceptable payload
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideAcceptableAuthPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01']],
            [['authorizationResponseCode' => 'NR01']],
            [['authorizationResponseCode' => 'TO01']],
        ];
    }

    /**
     * Provide payloads of data that should produce the provided response code.
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload and the expected result of getResponseCode
     */
    public function provideResponseCodePayloadAndCode()
    {
        return [
            [
                ['authorizationResponseCode' => 'AP01'],
                'APPROVED'
            ],
            [
                ['authorizationResponseCode' => 'NR01'],
                'TIMEOUT'
            ],
            [
                ['authorizationResponseCode' => 'TO01'],
                'TIMEOUT'
            ],
            // basically anything else should result in a `null` response code
            [
                ['authorizationResponseCode' => 'NC03'],
                null
            ],
        ];
    }
    /**
     * Provide payload data that will require AVS correction
     * @return array
     */
    public function provideAVSCorrectionRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01', 'avsResponseCode' => 'N']],
        ];
    }
    /**
     * Provide payload data that will not require AVS correction
     * @return array
     */
    public function provideAVSCorrectionNotRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'ND01', 'avsResponseCode' => 'N']],
            [['authorizationResponseCode' => 'AP01', 'avsResponseCode' => 'M']],
        ];
    }
    /**
     * Provide payload data that will require CVV2 correction
     * @return array
     */
    public function provideCVVCorrectionRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01', 'cvv2ResponseCode' => 'N']],
        ];
    }
    /**
     * Provide payload data that will not require CVV2 correction
     * @return array
     */
    public function provideCVVCorrectionNotRequiredPayload()
    {
        return [
            [['authorizationResponseCode' => 'AP01', 'cvv2ResponseCode' => 'M']],
            [['authorizationResponseCode' => 'ND01', 'cvv2ResponseCode' => 'N']],
        ];
    }
    public function provideAuthTimeoutPayload()
    {
        return [
            [['authorizationResponseCode' => 'TO01']],
            [['authorizationResponseCode' => 'NR01']]
        ];
    }
    /**
     * @return array
     */
    public function provideBooleanFromStringTests()
    {
        return [
            ["true", true],
            ["false", false],
            ["1", true],
            ["0", false],
            ["True", true],
            [null, null],
            [1, null],
            ["test", null]
        ];
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
     * @param string $testCase Spcifies a test case file to load - default to decrypted data
     * @return string
     */
    protected function loadXmlTestString($testCase = 'UnencryptedCardData')
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->load(__DIR__.'/Fixtures/'.$testCase.'/CreditCardAuthReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function loadXmlInvalidTestString()
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->load(__DIR__ . '/Fixtures/InvalidCreditCardAuthReply.xml');
        $string = $dom->C14N();

        return $string;
    }

    /**
     * @dataProvider provideBooleanFromStringTests
     */
    public function testBooleanFromString($value, $expected)
    {
        $payload = new CreditCardAuthReply($this->validatorIterator, $this->stubSchemaValidator);
        $method = new \ReflectionMethod('\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply', 'booleanFromString');
        $method->setAccessible(true);
        $actual = $method->invokeArgs($payload, [$value]);
        $this->assertEquals($expected, $actual);
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
     * Test that if a payload should pass validation but still produce an
     * XSD invalid payload, serialization should fail.
     * @param array $payloadData
     * @dataProvider provideInvalidPayload
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testSerializeWillFailWithXsdInvalidPayloadData(array $payloadData)
    {
        $this->stubSchemaValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $payload = $this->buildPayload($payloadData);
        $payload->serialize();
    }
    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testSerializeWillPass(array $payloadData, $testCase)
    {
        $payload = $this->buildPayload($payloadData);
        $domPayload = new \DOMDocument();
        $domPayload->preserveWhiteSpace = false;
        $domPayload->loadXML($payload->serialize());
        $serializedString = $domPayload->C14N();
        $domPayload->loadXML($this->loadXmlTestString($testCase));
        $expectedString = $domPayload->C14N();

        $this->assertEquals($expectedString, $serializedString);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailSchemaInvalid()
    {
        $this->stubSchemaValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->loadXmlInvalidTestString();

        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);
    }

    /**
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testDeserializeWillFailPayloadInvalid()
    {
        $this->stubValidator->expects($this->any())
            ->method('validate')
            ->will($this->throwException(new Payload\Exception\InvalidPayload));
        $xml = $this->loadXmlInvalidTestString();

        $newPayload = $this->createNewPayload();
        $newPayload->deserialize($xml);
    }

    /**
     * @param array $payloadData
     * @dataProvider provideValidPayload
     */
    public function testDeserializeWillPass(array $payloadData, $testCase)
    {
        $payload = $this->buildPayload($payloadData);
        $xml = $this->loadXmlTestString($testCase);
        $newPayload = $this->createNewPayload();
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
    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideAVSCorrectionRequiredPayload
     */
    public function testAvsCorrectionRequired(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertTrue($payload->getIsAVSCorrectionRequired());
    }
    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideAVSCorrectionNotRequiredPayload
     */
    public function testAvsCorrectionNotRequired(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertFalse($payload->getIsAVSCorrectionRequired());
    }
    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideCVVCorrectionRequiredPayload
     */
    public function testIsCVV2CorrectionRequired(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertTrue($payload->getIsCVV2CorrectionRequired());
    }
    /**
     * Test checking for if AVS corrections are needed.
     * @param array $payloadData
     * @dataProvider provideCVVCorrectionNotRequiredPayload
     */
    public function testIsCVV2CorrectionNotRequired(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertFalse($payload->getIsCVV2CorrectionRequired());
    }
    /**
     * Test checking for auth timeout responses.
     * @param  array  $payloadData
     * @dataProvider provideAuthTimeoutPayload
     */
    public function testIsAuthTimeout(array $payloadData)
    {
        $payload = $this->buildPayload($payloadData);
        $this->assertTrue($payload->getIsAuthTimeout());
    }
}
