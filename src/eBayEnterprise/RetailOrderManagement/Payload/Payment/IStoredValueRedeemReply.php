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
 * Interface IStoredValueRedeemReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IStoredValueRedeemReply extends IStoredValueRedeem
{
    const ROOT_NODE = 'StoredValueRedeemReply';

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
     * The amount redeemed from the card
     *
     * @return float
     */
    public function getAmountRedeemed();

    /**
     * @param float
     * @return self
     */
    public function setAmountRedeemed($amount);

    /**
     * The balance amount available on the card.
     *
     * @return float
     */
    public function getBalanceAmount();

    /**
     * @param float
     * @return self
     */
    public function setBalanceAmount($amount);

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCodeRedeemed();

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCodeRedeemed($code);

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getBalanceCurrencyCode();

    /**
     * @param string
     * @return self
     */
    public function setBalanceCurrencyCode($code);

    /**
     * Whether the gift card was successfully redeemed.
     * @return bool
     */
    public function wasRedeemed();
}
