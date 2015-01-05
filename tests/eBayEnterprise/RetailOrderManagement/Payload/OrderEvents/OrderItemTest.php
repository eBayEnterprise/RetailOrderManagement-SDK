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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator;

class OrderItemTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const FULL_FIXTURE_FILE = 'OrderItem.xml';

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        // use stub to allow validation success/failure to be scripted.
        $this->stubValidator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $this->validatorIterator = new ValidatorIterator([$this->stubValidator]);

        $this->fullPayload = $this->buildPayload([
            'setLineNumber' => 5,
            'setItemId' => '11-11111',
            'setQuantity' => 2,
            'setDescription' => 'the item description',
            'setTitle' => 'the item title',
            'setColor' => 'blue',
            'setColorId' => '0000ff',
            'setSize' => 'M',
            'setSizeId' => '2',
        ]);
    }

    protected function createNewPayload()
    {
        return new OrderItem($this->validatorIterator);
    }

    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/' . static::FULL_FIXTURE_FILE;
    }

    public function provideLineNumbers()
    {
        return [
            [12, 12],
            [22.5, 22],
            ['a string', null],
            [1000, null],
            [0, null],
            [new \stdClass, null],
        ];
    }

    /**
     * Test line number setter validation.
     *
     * @param mixed
     * @param int|null
     * @dataProvider provideLineNumbers
     */
    public function testSetLineNumber($lineNumber, $expected)
    {
        $payload = $this->buildPayload();
        $payload->setLineNumber($lineNumber);
        $this->assertSame($expected, $payload->getLineNumber());
    }
}
