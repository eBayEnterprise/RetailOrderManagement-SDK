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

namespace eBayEnterprise\RetailOrderManagement\Util;

use ReflectionClass;

trait TTestReflection
{
    /**
     * Use reflection to invoke a protected method.
     * @param mixed $object Must be instance of object as it will be used to invoke the method
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    protected function invokeRestrictedMethod($object, $method, $parameters = [])
    {
        $reflection = new ReflectionClass($object);
        $reflectedMethod = $reflection->getMethod($method);
        $reflectedMethod->setAccessible(true);

        return $reflectedMethod->invokeArgs($object, $parameters);
    }
    /**
     * Use reflection to set a protected property of some object.
     * @param mixed $object  Must be instance of object as it will be used when setting the reflected property
     * @param string $property Name of the property to set
     * @param mixed $value Value property will be set to
     * @return self
     */
    protected function setRestrictedPropertyValue($object, $property, $value)
    {
        $reflection = new ReflectionClass($object);
        $requestProperty = $reflection->getProperty($property);
        $requestProperty->setAccessible(true);
        $requestProperty->setValue($object, $value);
        return $this;
    }
    /**
     * Set a list of properties. Properties provided as key/value pairs of property => value.
     * @param mixed $object     Must be instance of object as it will be used when setting the reflected property values
     * @param array $properties Keys used as property names, values are what the property will be set to
     * @return self
     */
    protected function setRestrictedPropertyValues($object, $properties)
    {
        foreach ($properties as $property => $value) {
            $this->setRestrictedPropertyValue($object, $property, $value);
        }
        return $this;
    }
}
