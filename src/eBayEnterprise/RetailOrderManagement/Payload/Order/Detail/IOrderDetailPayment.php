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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IPaymentContainer;

interface IOrderDetailPayment extends IPaymentContainer, IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'Payment';

    /**
     * Billing Address references Destination Mailing Address
     *
     * @return string
     */
    public function getBillingAddress();

    /**
     * @param  string
     * @return self
     */
    public function setBillingAddress($billingAddress);

    /**
     * @return string
     */
    public function getRef();

    /**
     * @param  string
     * @return self
     */
    public function setRef($ref);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param  string
     * @return self
     */
    public function setStatus($status);
}
