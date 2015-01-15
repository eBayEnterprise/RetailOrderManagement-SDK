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

/**
 * Class RequiredFields
 * @package eBayEnterprise\RetailOrderManagement\Payload\Validator
 */
class RequiredFields implements Payload\IValidator
{
    /** @var string[] */
    protected $requiredDataAccessors;

    /**
     * Each accessor method provided, when called on a payload, must
     * return a non-null value.
     * @param string[] $requiredDataAccessors
     */
    public function __construct($requiredDataAccessors)
    {
        $this->requiredDataAccessors = $requiredDataAccessors;
    }

    /**
     * Validate that each required data accessor method returns an non-null
     * value.
     * @param  Payload\IPayload $payload
     * @return self
     * @throws Payload\Exception\InvalidPayload If any required field returns a null value.
     */
    public function validate(Payload\IPayload $payload)
    {
        $invalidDataAccessors = [];
        foreach ($this->requiredDataAccessors as $method) {
            if (is_null($payload->$method())) {
                $invalidDataAccessors[] = $method;
            }
        }
        if ($invalidDataAccessors) {
            throw new Payload\Exception\InvalidPayload(
                sprintf('%s Payload missing required data: %s.', get_class($payload), implode(', ', $invalidDataAccessors))
            );
        }
        return $this;
    }
}
