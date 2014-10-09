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
 * Interface IStoredValueBalanceRequest
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 */
interface IStoredValueBalanceRequest extends IPayload
{
    /**
     * Indicates if the Payment Account Number (PAN) is the actual number, or a representation of the number.
     *
     * @return bool true if the PAN is a token, false if it's the actual number
     */
    public function getPanIsToken();
    /**
     * @param bool $isToken
     * @return self
     */
    public function setPanIsToken($isToken);
    /**
     * Either a tokenized or plain text PAN.
     *
     * xsd restrictions: 1-22 characters
     * @see get/setPanIsToken
     * @return string
     */
    public function getCardNumber();
    /**
     * @param string $pan
     * @return self
     */
    public function setCardNumber($pan);
    /**
     * The PIN number used to authenticate a PAN
     *
     * xsd note: maxLength 4
     *           pattern (\d{3,4})?
     * return string
     */
    public function getPin();
    /**
     * @param string $pin
     * @return self
     */
    public function setPin($pin);
    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCode();
    /**
     * @param string $code
     * @return self
     */
    public function setCurrencyCode($code);
}
