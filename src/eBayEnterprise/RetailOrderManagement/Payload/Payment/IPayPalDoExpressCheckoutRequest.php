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
 * Interface IPayPalDoExpressCheckoutRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IPayPalDoExpressCheckoutRequest extends
    ICurrencyCode,
    ILineItemContainer,
    IPayPalDoExpressCheckout,
    IShippingAddress
{
    const ROOT_NODE = 'PayPalDoExpressCheckoutRequest';

    /**
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * xsd restrictions: 1-40 characters
     * @return string
     */
    public function getRequestId();

    /**
     * @param string
     * @return self
     */
    public function setRequestId($requestId);

    /**
     * Token from the PayPalSetExpressCheckoutReply, can be retrieved from the PayPalGetExpressCheckoutReply
     * or the URL the customer was redirected with from PayPal.
     *
     * @return string
     */
    public function getToken();

    /**
     * @param string
     * @return self
     */
    public function setToken($token);

    /**
     * Unique identifier of the customer's PayPal account, can be retrieved from the PayPalGetExpressCheckoutReply
     * or the URL the customer was redirected with from PayPal.
     *
     * @return string
     */
    public function getPayerId();

    /**
     * @param string
     * @return self
     */
    public function setPayerId($id);

    /**
     * The amount to authorize
     *
     * xsd note: minimum value 0
     *           maximum precision 2 decimal places
     * @return float
     */
    public function getAmount();

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);

    /**
     * PickUpStoreId refers to store name/number for ship-to-store/in-store-pick up like "StoreName StoreNumber".
     * Optional except during ship-to-store delivery method.
     *
     * @return string
     */
    public function getPickUpStoreId();

    /**
     * @param string
     * @return self
     */
    public function setPickUpStoreId($id);

    /**
     * The name of the person shipped to like "FirsName LastName".
     *
     * @return string
     */
    public function getShipToName();

    /**
     * @param string
     * @return self
     */
    public function setShipToName($name);

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($code);
}
