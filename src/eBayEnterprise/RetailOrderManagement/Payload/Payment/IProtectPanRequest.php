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
 * Interface IProtectPanRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IProtectPanRequest extends IPayload
{
    const ROOT_NODE = 'ProtectPanRequest';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/checkout/1.0/Payment-Service-ProtectPan-1.0.xsd';
    const TENDER_CLASS_PL_CC = 'PrivateLabelCreditCard';
    const TENDER_CLASS_CC = 'CreditCard';
    const TENDER_CLASS_SV = 'StoredValue';

    /**
     * Actual Payment Account Number (PAN). Payment card numbers are found on payment cards,
     * such as credit cards and debit cards, as well as stored-value cards, gift cards and other similar cards.
     * Some card issuers refer to the card number as the primary account number or PAN.
     *
     * xsd restrictions: 3-50 characters
     * @return string
     */
    public function getPaymentAccountNumber();

    /**
     * @param string
     * @return self
     */
    public function setPaymentAccountNumber($paymentAccountNumber);

    /**
     * Tender class represents type of Payment Account Number (PAN):
     * PrivateLabelCreditCard, CreditCard and StoredValue (gift card).
     * TenderClass used for API requests like Tender Type Look Up API or Protect Pan API.
     * NOTE: Use CreditCard tender class type instead of PrivateLabelCreditCard for private
     * label credit card account numbers; PrivateLabelCreditCard is treated as CreditCard
     * tender class type and available here for backward compatibility purpose only.
     *
     * @return string
     */
    public function getTenderClass();

    /**
     * @param string
     * @return self
     */
    public function setTenderClass($tenderClass);
}
