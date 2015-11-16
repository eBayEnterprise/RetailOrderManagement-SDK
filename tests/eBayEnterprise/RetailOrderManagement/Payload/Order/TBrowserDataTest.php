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

class TBrowserDataTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->payload = $this->getMockForTrait('\eBayEnterprise\RetailOrderManagement\Payload\Order\TBrowserData');
    }

    /**
     * When setting an IP address to the browser data payload, only valid IPv4
     * addresses should be accepted and stored. Non-IPv4 address should be ignored.
     *
     * @param string
     * @param string|null
     * @dataProvider provideIpAddresses
     */
    public function testFilterIpAddresses($address, $expected)
    {
        $this->payload->setIpAddress($address);
        $this->assertSame($this->payload->getIpAddress(), $expected);
    }

    /**
     * Provide a value to set as the IP address of the payload and the value
     * that should be returned when attempting to retrieve the IP address
     * from the payload. When the IP address is acceptable for the SDK,
     */
    public function provideIpAddresses()
    {
        return [
            ['127.0.0.1', '127.0.0.1'],
            ['::1', null],
            ['2001:0db8:0000:0000:0000:ff00:0042:8329', null],
            ['foo', null],
        ];
    }
}
