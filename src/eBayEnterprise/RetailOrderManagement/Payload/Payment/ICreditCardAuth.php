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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

/**
 * Interface ICreditCardAuth
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 */
interface ICreditCardAuth extends IPayload, IPaymentContext
{
    // XML related values - document root node, XMLNS and name of the xsd schema file
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ENCRYPTED_CVV_NODE = 'EncryptedCardSecurityCode';
    const RAW_CVV_NODE = 'CardSecurityCode';
    const XSD = '/checkout/1.0/Payment-Service-CreditCardAuth-1.0.xsd';
    // API response codes relevant to payload success/failure and OMS response code
    const AUTHORIZATION_APPROVED = 'AP01';
    const AUTHORIZATION_TIMEOUT_PAYMENT_PROVIDER = 'TO01';
    const AUTHORIZATION_TIMEOUT_CARD_PROCESSOR = 'NR01';
    // response codes that are to be reported to the OMS
    const APPROVED_RESPONSE_CODE = 'APPROVED';
    const TIMEOUT_RESPONSE_CODE = 'TIMEOUT';
}
