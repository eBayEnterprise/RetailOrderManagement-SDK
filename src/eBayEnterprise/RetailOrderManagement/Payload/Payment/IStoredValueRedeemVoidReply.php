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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

/**
 * Interface IStoredValueRedeemVoidReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IStoredValueRedeemVoidReply extends IStoredValueRedeemVoid
{
    const ROOT_NODE = 'StoredValueRedeemVoidReply';

    /**
     * The result of the request transaction.
     * In the case of a StoredValue, you would never accept an order unless the redeem was successful.
     *
     * xsd restriction: possible values "Fail", "Success", "Timeout"
     * @return string
     */
    public function getResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setResponseCode($code);

    /**
     * Whether the gift card redeem was successfully voided.
     * @return bool
     */
    public function wasVoided();
}
