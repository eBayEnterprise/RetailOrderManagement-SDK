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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\NullLogger;

class TaxTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const PAYLOAD_CLASS = '\eBayEnterprise\RetailOrderManagement\Payload\Order\Tax';

    /** @var ITax */
    protected $payload;

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
        $this->payload = $this->createNewPayload();
    }

    /**
     * @return array
     */
    public function providerEffectiveRateValueDoNotGetRounded()
    {
        return [
            [0.065],
            [0.0651],
            [0.075100],
            [0.075109],
        ];
    }

    /**
     * Scenario: Test that the effective rate setter method will not round the value it stores
     * Given a float value of any precision
     * When setting the effective rate in the Tax payload
     * Then, the value will maintain the exact value without rounding it.
     *
     * @param float
     * @dataProvider providerEffectiveRateValueDoNotGetRounded
     */
    public function testEffectiveRateValueDoNotGetRounded($effectiveRate)
    {
        $this->assertSame($this->payload, $this->payload->setEffectiveRate($effectiveRate));
        $this->assertSame($effectiveRate, $this->payload->getEffectiveRate());
    }

    /**
     * Instantiate new payload.
     * @return Tax
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory->buildPayload(self::PAYLOAD_CLASS, null, null, new NullLogger());
    }
}
