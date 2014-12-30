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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;

interface IOrderShipped extends IPayload, ICurrency, ILoyaltyProgramCustomer, IOrderEvent, IOrderItemContainer, IPaymentContainer, ISummaryAmounts, IShippedAmounts, ITaxDescriptionContainer
{
    const ROOT_NODE = 'OrderShipped';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/events/1.0/events/Order-Shipping-Event-1.0.xsd';
    const MAILING_ADDRESS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IMailingAddress';
    const STORE_FRONT_DETAILS_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IStoreFrontDetails';

    /**
     * Get the destination the items were shipped to. May be a customer
     * mailing address or store front address if shipped to a store.
     *
     * @return IDestination
     */
    public function getShippingDestination();
    /**
     * @param IDestination
     * @return self
     */
    public function setShippingDestination(IDestination $shippingDestination);
}
