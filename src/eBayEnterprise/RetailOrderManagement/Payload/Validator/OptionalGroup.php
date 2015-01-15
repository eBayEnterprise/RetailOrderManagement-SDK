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
 * Class OptionalGroup
 *
 * Validates an optional payload group. Valid if all of the given fields are
 * set (non-null) or none of the fields are set (null).
 *
 * @package eBayEnterprise\RetailOrderManagement\Payload\Validator
 */
class OptionalGroup implements Payload\IValidator
{
    /** @var string[] */
    protected $requiredDataAccessors;

    /**
     * @param string[] $requiredDataAccessors
     */
    public function __construct($requiredDataAccessors)
    {
        $this->requiredDataAccessors = $requiredDataAccessors;
    }

    /**
     * Each required data accessor method must return a non-null value.
     *
     * Since the group is optional the payload may not include it. In that
     * case all of the required accessor methods must return null otherwise
     * the optional group may be only partially set up.
     *
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

        // make sure either all required accessors were null or all accessors where non-null
        if ($invalidDataAccessors && (count($invalidDataAccessors) !== count($this->requiredDataAccessors))) {
            throw new Payload\Exception\InvalidPayload(
                sprintf('%s Payload optional group missing required data: %s.', get_class($payload), implode(', ', $invalidDataAccessors))
            );
        }

        return $this;
    }
}
