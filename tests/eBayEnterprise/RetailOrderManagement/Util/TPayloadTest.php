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

use eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemContainer;

trait TPayloadTest
{
    /**
     * @return \eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap
     */
    protected function stubPayloadMap()
    {
        /** @var \eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap $payloadMap */
        $payloadMap = $this->getMock('\eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap');
        $payloadMap
            ->expects($this->once())
            ->method('getConcreteType')
            ->will($this->returnValueMap([
                [
                    ILineItemContainer::ITERABLE_INTERFACE,
                    '\eBayEnterprise\RetailOrderManagement\Payload\Payment\LineItemIterable'
                ]
            ]));
        $payloadMap
            ->expects($this->any())
            ->method('hasMappingForType')
            ->will($this->returnValueMap([[ILineItemContainer::ITERABLE_INTERFACE, true]]));
        $payloadMap
            ->expects($this->any())
            ->method('getOverrideWithMapping')
            ->will($this->returnSelf());
        return $payloadMap;
    }

    /**
     * Returns a mock object for the specified class.
     *
     * @param  string $originalClassName Name of the class to mock.
     * @param  array|null $methods When provided, only methods whose names are in the array are replaced
     *                             with a configurable test double. The behavior of the other methods is not changed.
     *                             Providing null means that no methods will be replaced.
     * @param  array $arguments Parameters to pass to the original class' constructor.
     * @param  string $mockClassName Class name for the generated test double class.
     * @param  boolean $callOriginalConstructor Can be used to disable the call to the original class' constructor.
     * @param  boolean $callOriginalClone Can be used to disable the call to the original class' clone constructor.
     * @param  boolean $callAutoload Can be used to disable __autoload() during the generation of the test double class.
     * @param  boolean $cloneArguments
     * @param  boolean $callOriginalMethods
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \PHPUnit_Framework_Exception
     * @since  Method available since Release 3.0.0
     */
    abstract public function getMock(
        $originalClassName,
        $methods = [],
        array $arguments = [],
        $mockClassName = '',
        $callOriginalConstructor = true,
        $callOriginalClone = true,
        $callAutoload = true,
        $cloneArguments = false,
        $callOriginalMethods = false
    );

    /**
     * @param  array $valueMap
     * @return \PHPUnit_Framework_MockObject_Stub_ReturnValueMap
     * @since  Method available since Release 3.6.0
     */
    abstract public static function returnValueMap(array $valueMap);

    /**
     * Returns the current object.
     *
     * This method is useful when mocking a fluent interface.
     *
     * @return \PHPUnit_Framework_MockObject_Stub_ReturnSelf
     * @since  Method available since Release 3.6.0
     */
    abstract public static function returnSelf();
}
