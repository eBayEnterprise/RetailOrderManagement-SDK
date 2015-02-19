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

use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayloadTest;
use eBayEnterprise\RetailOrderManagement\Payload\ValidatorIterator;
use Psr\Log\NullLogger;

class LoyaltyProgramTest extends \PHPUnit_Framework_TestCase
{
    use TPayloadTest;

    const FULL_FIXTURE_FILE = 'LoyaltyProgram.xml';

    /**
     * Setup a stub validator and validator iterator for each payload to use
     */
    public function setUp()
    {
        $this->payloadFactory = new PayloadFactory();
        $this->fullPayload = $this->buildPayload(['setAccount' => 'loyalty acct', 'setProgram' => 'loyalty program']);
        $attributes = $this->fullPayload->getCustomAttributes();
        $attr = $attributes->getEmptyCustomAttribute();
        $attr->setKey('key')->setValue('value');
        $attributes->offsetSet($attr);
        $this->fullPayload->setCustomAttributes($attributes);
    }

    /**
     * Get a new LoyaltyProgram payload. Each payload will contain a
     * ValidatorIterator (self::validatorIterator) containing a single mocked
     * validator (self::$stubValidator).
     * @return LoyaltyProgram
     */
    protected function createNewPayload()
    {
        return $this->payloadFactory->buildPayload('\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\LoyaltyProgram', null, null, new NullLogger());
    }

    protected function getCompleteFixtureFile()
    {
        return __DIR__ . '/Fixtures/' . static::FULL_FIXTURE_FILE;
    }

    public function testDeserialize()
    {
        $payload = $this->buildPayload();
        $payload->deserialize($this->loadXmlTestString($this->getCompleteFixtureFile()));

        // validate payload data...
        $this->assertSame($this->fullPayload->getAccount(), $payload->getAccount());
        $this->assertSame($this->fullPayload->getProgram(), $payload->getProgram());
        // ...and sub-payload data
        $expectedCustomAttributes = $this->fullPayload->getCustomAttributes();
        $actualCustomAttributes = $payload->getCustomAttributes();
        $this->assertSame($expectedCustomAttributes->count(), $actualCustomAttributes->count());
    }
}
