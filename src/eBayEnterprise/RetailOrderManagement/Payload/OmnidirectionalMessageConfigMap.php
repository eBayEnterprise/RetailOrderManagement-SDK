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
    $orderEvents = '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents';
    $map = [];
    $map['OrderBackorder'] = "$orderEvents\OrderBackorder";
    $map['OrderCancelled'] = "$orderEvents\OrderCancel";
    $map['OrderCreditIssued'] = "$orderEvents\OrderCreditIssued";
    $map['OrderRejected'] = "$orderEvents\OrderRejected";
    $map['OrderShipped'] = "$orderEvents\OrderShipped";
    $map['OrderCreditIssued'] = '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\OrderCreditIssued';
    $map['Test'] = "$orderEvents\TestMessage";
    return $map;
});
