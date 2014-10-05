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

class ValidatorIteratorTest extends \PHPUnit_Framework_TestCase
{
    protected static $validator;
    /**
     * Create a new ValidatorIterator with a single Validator
     * @return ValidatorIterator
     */
    public function testConstruct()
    {
        // create and hold a static reference to the validator in the iterator
        self::$validator = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $iterator = new ValidatorIterator(array(self::$validator));
        // new iterator with one validator should have key of 0 and be valid
        $this->assertSame(0, $iterator->key());
        $this->assertTrue($iterator->valid());
        return $iterator;
    }
    /**
     * Current of new iterator should return the first validator
     * @param  ValidatorIterator $iterator [description]
     * @return ValidatorIterator
     * @depends testConstruct
     */
    public function testCurrent(ValidatorIterator $iterator)
    {
        $this->assertSame(self::$validator, $iterator->current());
        return $iterator;
    }
    /**
     * Next should update the offset to the next item
     * @param  ValidatorIterator $iterator
     * @return ValidatorIterator
     * @depends testCurrent
     */
    public function testNextUpdatesKey(ValidatorIterator $iterator)
    {
        $iterator->next();
        $this->assertSame(1, $iterator->key());
        return $iterator;
    }
    /**
     * Once the offset no longer points at an item, the iterator is no longer valid
     * @param ValidatorIterator $iterator
     * @return ValidatorIterator
     * @depends testNextUpdatesKey
     */
    public function testInvalidIteratorIsNotValid(ValidatorIterator $iterator)
    {
        $this->assertFalse($iterator->valid());
        return $iterator;
    }
    /**
     * Rewinding the iterator should set the offset back to the beginning
     * @param  ValidatorIterator $iterator
     * @depends testInvalidIteratorIsNotValid
     */
    public function testRewindResetsKey(ValidatorIterator $iterator)
    {
        $iterator->rewind();
        $this->assertSame(0, $iterator->key());
        return $iterator;
    }
    /**
     * Tryint to construct a ValidatorIterator with items that are not
     * IValidators should be an error/exception
     * @expectedException \PHPUnit_Framework_Error
     */
    public function testCannotConstructWithInvalidItems()
    {
        new ValidatorIterator(array('not an IValidator'));
    }
    /**
     * The ValidatorIterator should be ignore the original keys of the given items,
     * may be keyed by ints, strings, w/e. Basically testing that just using the
     * array given to the constructor may not work.
     */
    public function testIteratorIgnoresOriginalKeys()
    {
        $threeValidator = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $fooValidator = $this->getMock('eBayEnterprise\RetailOrderManagement\Payload\IValidator');
        $iterator = new ValidatorIterator(array(3 => $threeValidator, 'foo' => $fooValidator));
        $this->assertSame($threeValidator, $iterator->current());
        $iterator->next();
        $this->assertSame($fooValidator, $iterator->current());
    }
}
