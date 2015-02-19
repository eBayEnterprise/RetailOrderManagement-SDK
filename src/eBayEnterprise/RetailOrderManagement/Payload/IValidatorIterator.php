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
 * Interface IValidatorIterator
 * @package eBayEnterprise\RetailOrderManagement\Payload
 *
 * For processing batches of validators, one at a time.
 *
 * Implementation note: you may want to implement this as an
 * \OuterIterator, where the inner iterator yields each serialized payload.
 *
 * @see \Iterator
 * @method bool valid should not be used to validate the payload itself;
 * only the iterator position.
 */
interface IValidatorIterator extends \Iterator
{
    /**
     * @return IValidator
     */
    public function current();
}
