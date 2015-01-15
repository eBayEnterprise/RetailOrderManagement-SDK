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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

interface IOrderContextCustomAttributeContainer
{
    const ORDER_CONTEXT_CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeIterable';

    /**
     * Get all custom attributes within the container.
     * @return ICustomAttributeIterable
     */
    public function getOrderContextCustomAttributes();

    /**
     * @param ICustomAttributeIterable
     * @return self
     */
    public function setOrderContextCustomAttributes(ICustomAttributeIterable $orderContextCustomAttributes);
}
