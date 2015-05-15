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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;

class QuantityItemTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
    }

    /**
     * When getting and setting a line id, the id value that goes into
     * setLineId should come back out of getLineId. Internally, values
     * are prefixed and de-prefixed to prevent invalid values from being
     * added to the payload. Test ensures that this is done properly.
     */
    public function testLineId()
    {
        $lineId = '5';
        $payload = $this->createNewPayload();
        $payload->setLineId($lineId);
        $this->assertSame($lineId, $payload->getLineId());
    }

    /**
     * When getting the line id of a payload, if one is not already
     * set, a unique line id for the item should be generated.
     */
    public function testAutogenerateLineId()
    {
        $payload = $this->createNewPayload();
        $this->assertNotNull($payload->getLineId());
    }

    /**
     * When setting a line id, the value should be prefixed with consistent,
     * non-numeric value.
     */
    public function testPrefixLineId()
    {
        $lineId = '5';
        $payload = $this->createNewPayload();
        $payload->setLineId($lineId);
        // The id value, the one that will be included in the serialized
        // format, is available via the protected "getId" method.
        $getIdMethod = new \ReflectionMethod($payload, 'getId');
        $getIdMethod->setAccessible(true);
        // Ensure that the id value to be used in the serialized payload
        // has been properly prefixed - currently that means with a single
        // underscode character.
        $this->assertSame('_' . $lineId, $getIdMethod->invoke($payload));
    }

    /**
     * Get a new ValidationRequest payload.
     * @return ValidationRequest
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory
            ->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\Inventory\QuantityItem');
    }
}
