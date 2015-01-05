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

class PayPalGetExpressCheckoutRequestTest extends \PHPUnit_Framework_TestCase
{
    use \eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayloadTest;

    protected $payloadProperties = [
        'setOrderId' => '1234567',
        'setToken' => 'EC-5YE59312K56892714',
        'setCurrencyCode' => 'USD',
    ];

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->fullPayload = $this->buildPayload($this->payloadProperties);
    }

    /**
     * Get a new PayPalGetExpressCheckoutRequest payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return PayPalGetExpressCheckoutRequest
     */
    protected function createNewPayload()
    {
        return new PayPalGetExpressCheckoutRequest($this->validatorIterator, $this->stubSchemaValidator);
    }

   /**
     * Load some invalid XML from a fixture file and canonicalize it. Returns
     * the canonical XML string.
     * @return string
     */
    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/PayPalGetExpressCheckoutRequestTest.xml';
    }
}
