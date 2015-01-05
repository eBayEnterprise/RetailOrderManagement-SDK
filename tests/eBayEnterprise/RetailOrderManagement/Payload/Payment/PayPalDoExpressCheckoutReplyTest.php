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

class PayPalDoExpressCheckoutReplyTest extends \PHPUnit_Framework_TestCase
{
    use Payload\TTopLevelPayloadTest;
    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new Payload\ValidatorIterator([$this->stubValidator]);
        $this->stubSchemaValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator');
        $this->fullPayload = $this->buildPayload([
            'setOrderId' => '1234567',
            'setResponseCode' => 'Success',
            'setTransactionID' => 'O-3A919253XG323924A',
            'setPaymentStatus' => 'Pending',
            'setPendingReason' => 'order',
            'setReasonCode' => 'none'
        ]);
    }

    /**
     * Data provider for invalid payloads
     * @return array[] Array of arg arrays, each containing a set of payload data suitable for self::buildPayload
     */
    public function provideInvalidPayload()
    {
        return [
            [[]] // Empty payload should fail validation.
        ];
    }

    /**
     * Get a new PayPalDoExpressCheckoutReply payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return PayPalDoExpressCheckoutReply
     */
    protected function createNewPayload()
    {
        return new PayPalDoExpressCheckoutReply($this->validatorIterator, $this->stubSchemaValidator);
    }

    /**
     * get the fixture file name
     * @return string
     */
    protected function getCompleteFixtureFile()
    {
        return __DIR__ . "/Fixtures/PayPalDoExpressCheckoutReplyTest.xml";
    }
}
