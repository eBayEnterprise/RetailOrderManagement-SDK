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

/**
 * Class ValidatorIterator
 * @package eBayEnterprise\RetailOrderManagement\Payload
 */
class ValidatorIterator implements IValidatorIterator
{
    /** @var IValidator[] */
    protected $validators = [];
    /** @var int */
    protected $offset = 0;

    /**
     * Supply the list of validators
     * @param IValidator[] $validators
     */
    public function __construct(array $validators)
    {
        // Add validators via a protected `add` method which can be type-hinted.
        // Also ensures that array keys of the input array won't interfere with
        // keeping track of the internal offset.
        foreach ($validators as $validator) {
            $this->add($validator);
        }
        $this->offset = 0;
    }

    /**
     * Add an IValidator to the list of validators - only done via the constructor
     * @param IValidator $validator
     * @return self
     */
    protected function add(IValidator $validator)
    {
        $this->validators[] = $validator;
        return $this;
    }

    /**
     * Get the validator at the current offset
     * @return IValidator
     */
    public function current()
    {
        return $this->validators[$this->offset];
    }

    /**
     * Get the current index/offset
     * @return int
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * Increment the offset to the next value
     */
    public function next()
    {
        $this->offset++;
    }

    /**
     * Reset the offset to the beginning
     */
    public function rewind()
    {
        $this->offset = 0;
    }

    /**
     * Check if the current offset is valid
     * @return bool
     */
    public function valid()
    {
        return $this->offset < count($this->validators);
    }
}
