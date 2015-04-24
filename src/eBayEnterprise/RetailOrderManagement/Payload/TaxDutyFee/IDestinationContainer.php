<?php
/**
 * Copyright (c) 2013-2015 eBay Enterprise, Inc.
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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationIterable;

interface IDestinationContainer
{
    const DESTINATION_ITERABLE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestinationIterable';

    /**
     * Billing, shipping and delivery destinations for the order. May be
     * customer mailing addresses, a store locations and email addresses.
     *
     * @return IDestinationIterable
     */
    public function getDestinations();

    /**
     * @param IDestinationIterable
     * @return self
     */
    public function setDestinations(IDestinationIterable $destinations);
}
