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

namespace eBayEnterprise\RetailOrderManagement\Payload\Validator;

use eBayEnterprise\RetailOrderManagement\Payload;

class RequiredFieldsTest extends \PHPUnit_Framework_TestCase
{
    /** @var string[] */
    protected $requiredFields = ['getRequiredField'];
    /** @var Payload\IPayload */
    protected $payload;

    public function setUp()
    {
        $this->payload = $this->getMockBuilder('eBayEnterprise\RetailOrderManagement\Payload\IPayload')
            ->setMethods($this->requiredFields)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
    }
    /**
     * Test that payloads missing required data causes an InvalidPayload exception.
     *
     * @expectedException \eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload
     */
    public function testValidateInvalidPayload()
    {
        $validator = new RequiredFields($this->requiredFields);
        $validator->validate($this->payload);
    }
    /**
     * Test that validating a valid payload simply returns self.
     */
    public function testValidateValidPayload()
    {
        // set up the payload to return something other than null for each
        // required field
        foreach ($this->requiredFields as $method) {
            $this->payload->expects($this->any())
                ->method($method)
                ->will($this->returnValue('not null'));
        }
        $validator = new RequiredFields($this->requiredFields);
        $this->assertSame($validator, $validator->validate($this->payload));
    }
}
