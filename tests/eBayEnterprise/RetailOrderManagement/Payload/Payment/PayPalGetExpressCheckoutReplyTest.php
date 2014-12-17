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
use ReflectionProperty;

class PayPalGetExpressCheckoutReplyTest extends \PHPUnit_Framework_TestCase
{
    use Payload\TTopLevelPayloadTest;

    /** @var Payload\IPayloadMap (stub) */
    protected $stubPayloadMap;
    /** @var Payload\IPayloadFactory */
    protected $stubPayloadFactory;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->stubPayloadMap = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap');
        $this->stubPayloadFactory = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadFactory');
        $this->fullPayload = $this->buildPayload([
            'setOrderId' => '0005400400154',
            'setResponseCode' => 'Success',
            'setPayerEmail' => 'tan_1329493113_per@trueaction.com',
            'setPayerId' => 'P9PMKWC782MJ8',
            'setPayerStatus' => 'verified',
            'setPayerPhone' => '848-129-8433',
            'setPayerCountry' => 'US',
            'setPayerNameHonorific' => '',
            'setPayerLastName' => 'Buyer',
            'setPayerMiddleName' => '',
            'setPayerFirstName' => 'TAN',
            'setBillingLines' => "Street 1\nStreet 2\nStreet 3\nStreet 4",
            'setBillingCity' => 'King of Prussia',
            'setBillingMainDivision' => 'PA',
            'setBillingCountryCode' => 'US',
            'setBillingPostalCode' => '19406',
            'setShipToLines' => "Street 1\nStreet 2\nStreet 3\nStreet 4",
            'setShipToCity' => 'King of Prussia',
            'setShipToMainDivision' => 'PA',
            'setShipToCountryCode' => 'US',
            'setShipToPostalCode' => '19406',
        ]);
    }

    /**
     * Get a new PayPalGetExpressCheckoutReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return PayPalGetExpressCheckoutReply
     */
    protected function createNewPayload()
    {
        $payload = new PayPalGetExpressCheckoutReply(
            $this->validatorIterator,
            $this->stubSchemaValidator,
            $this->stubPayloadMap
        );
        $reflection = new ReflectionProperty($payload, 'payloadFactory');
        $reflection->setAccessible(true);
        $reflection->setValue($payload, $this->stubPayloadFactory);
        return $payload;
    }

    /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function getCompleteFixtureFile()
    {
        return __DIR__ . "/Fixtures/PayPalGetExpressCheckoutReplyTest.xml";
    }
}
