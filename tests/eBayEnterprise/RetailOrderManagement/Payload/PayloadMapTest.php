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

namespace eBayEnterprise\RetailOrderManagement\Payload;

class PayloadMapTest extends \PHPUnit_Framework_TestCase
{
    const KNOWN_TYPE = '\known\type';
    const UNKNOWN_TYPE = '\unknown\type';
    const OTHER_KNOWN_TYPE = '\other\known\type';
    const CONCRETE_TYPE = '\known\concrete\type';
    /** @var IPayloadMap stub for testing merged maps */
    protected $otherMap;
    /** @var IPayloadMap (SUT) */
    protected $payloadMap;

    public function setUp()
    {
        $this->otherMap = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap');
        $this->otherMap->expects($this->any())
            ->method('getConcreteType')
            ->will($this->returnValueMap([
                [self::OTHER_KNOWN_TYPE, self::CONCRETE_TYPE],
            ]));
        $this->otherMap->expects($this->any())
            ->method('hasMappingForType')
            ->will($this->returnValueMap([
                [self::OTHER_KNOWN_TYPE, true],
            ]));
        $this->payloadMap = new PayloadMap([self::KNOWN_TYPE => self::CONCRETE_TYPE]);
    }
    public function provideTypeName()
    {
        return [
            [self::KNOWN_TYPE],
            [self::UNKNOWN_TYPE],
        ];
    }
    /**
     * Test getting a concrete type for an abstract type
     * @param  string $abstractType
     * @dataProvider provideTypeName
     */
    public function testGetConcreteType($abstractType)
    {
        if ($abstractType !== self::KNOWN_TYPE) {
            $this->setExpectedException('eBayEnterprise\RetailOrderManagement\Payload\Exception\UnsupportedPayload');
        }
        $this->assertSame(
            self::CONCRETE_TYPE,
            $this->payloadMap->getConcreteType($abstractType)
        );
    }
    /**
     * Test checking for a payload map to know about an abstract type
     * @param  string $abstractType
     * @dataProvider provideTypeName
     */
    public function testHasMappingForType($abstractType)
    {
        $this->assertSame(
            $abstractType === self::KNOWN_TYPE,
            $this->payloadMap->hasMappingForType($abstractType)
        );
    }
    /**
     * Test that when another payload map is merged, it will be used to lookup
     * types.
     */
    public function testMerge()
    {
        // payload map doesn't know about the type
        $this->assertFalse($this->payloadMap->hasMappingForType(self::OTHER_KNOWN_TYPE));
        $this->payloadMap->merge($this->otherMap);
        // other map does
        $this->assertTrue($this->payloadMap->hasMappingForType(self::OTHER_KNOWN_TYPE));
        $this->assertSame(self::CONCRETE_TYPE, $this->payloadMap->getConcreteType(self::OTHER_KNOWN_TYPE));
    }
}
