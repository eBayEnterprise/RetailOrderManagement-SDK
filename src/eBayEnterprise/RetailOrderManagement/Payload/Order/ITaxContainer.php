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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

interface ITaxContainer
{
    const TAX_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\ITaxIterable';

    /**
     * Get all taxes in the container.
     *
     * @return ITaxIterable
     */
    public function getTaxes();

    /**
     * @param ITaxIterable
     * @return self
     */
    public function setTaxes(ITaxIterable $taxes);

    /**
     * Item category for tax purposes.
     *
     * restrictions: optional
     * @return string
     */
    public function getTaxClass();

    /**
     * @param string
     * @return self
     */
    public function setTaxClass($taxClass);
}
