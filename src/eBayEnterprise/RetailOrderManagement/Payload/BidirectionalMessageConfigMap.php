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

/**
 * Wrap include in a function to allow variables while protecting scope.
 * @return array mapping of config keys to message payload types for bidirectional api operations
 */
return call_user_func(function () {
    $map = [];
    $map['address/validate'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Address\ValidationRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Address\ValidationReply',
    ];
    $map['orders/create'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderCreateRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Order\OrderCreateReply',
    ];
    $map['payments/creditcard/auth'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\CreditCardAuthReply',
    ];
    $map['payments/storedvalue/balance'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueBalanceReply',
    ];
    $map['payments/storedvalue/redeem'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemReply',
    ];
    $map['payments/storedvalue/redeemvoid'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\StoredValueRedeemVoidReply',
    ];
    $map['payments/paypal/setExpress'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalSetExpressCheckoutReply',
    ];
    $map['payments/paypal/getExpress'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalGetExpressCheckoutReply',
    ];
    $map['payments/paypal/doExpress'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoExpressCheckoutReply',
    ];
    $map['payments/paypal/doAuth'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoAuthorizationReply',
    ];
    $map['payments/paypal/void'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoVoidRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\Payment\PayPalDoVoidReply',
    ];
    $map['taxes/quote'] = [
        'request' => '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteRequest',
        'reply' => '\eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee\TaxDutyFeeQuoteReply',
    ];
    return $map;
});
