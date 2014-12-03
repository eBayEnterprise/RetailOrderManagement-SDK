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

interface IPayPalSetExpressCheckoutRequest extends
    ICurrencyCode,
    ILineItemContainer,
    IPayPalSetExpressCheckout,
    IShippingAddress
{
    const ROOT_NODE = 'PayPalSetExpressCheckoutRequest';

    /**
     * URL to which the customer's browser is returned after choosing to pay with PayPal.
     * PayPal recommends that the value be the final review page on which the customer confirms the order and payment.
     *
     * @return string
     */
    public function getReturnUrl();

    /**
     * @param string
     * @return self
     */
    public function setReturnUrl($url);

    /**
     * URL to which the customer is returned if the customer does not approve the use of PayPal to pay you.
     * PayPal recommends that the value be the original page on which the customer chose to pay with PayPal.
     *
     * @return string
     */
    public function getCancelUrl();

    /**
     * @param string
     * @return self
     */
    public function setCancelUrl($url);

    /**
     * Locale of pages displayed by PayPal during Express Checkout.
     * PayPal supports a subset of the BCP 47 codes. See the PayPal documentation for specifics.
     *
     * @link https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
     * @return string
     */
    public function getLocaleCode();

    /**
     * @see IPayPalSetExpressCheckoutRequest::getLocaleCode
     * @param  string
     * @return self
     */
    public function setLocaleCode($lc);

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
     * If true, PayPal will display the shipping address provided in the payload.
     * Otherwise PayPal will display whatever shipping address it has for the customer
     * and won't let the customer edit it.
     * Consider setting this flag implicitly based on whether or not an address is provided.
     * And simply implement the getter/setter to allow overriding as an edge case.
     *
     * @return bool
     */
    public function getAddressOverride();

    /**
     * @param bool
     * @return self
     */
    public function setAddressOverride($override);

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
