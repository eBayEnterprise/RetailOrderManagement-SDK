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

interface IOrderAccepted extends IPayload, IOrderEvent, ICustomer, ICurrency, IOrderItemContainer, IPaymentContainer, ISummaryAmounts
{
    const ROOT_NODE = 'OrderAccepted';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/events/1.0/events/Order-Accepted-Event-1.0.xsd';

    /**
     * VAT Tax amount
     *
     * xsd restriction: 2 decimal, non-negative
     * @return float
     */
    public function getVatTaxAmount();
    /**
     * @param float
     * @return self
     */
    public function setVatTaxAmount($vatTaxAmount);
    /**
     * Get the source of the order
     *
     * @return string
     */
    public function getOrderAcceptedSource();
    /**
     * @param string
     * @return self
     */
    public function setOrderAcceptedSource($orderAcceptedSource);
}
