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
 * Interface IPaymentAccountUniqueId
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 *
 * <PaymentAccountUniqueId isToken="true">
 *     xDAofm6LAMiACkz5
 * </PaymentAccountUniqueId>
 */
interface IPaymentAccountUniqueId
{
    const RAW_CARD_NUMBER_NODE = 'PaymentAccountUniqueId';
    const ENCRYPTED_CARD_NUMBER_NODE = 'EncryptedPaymentAccountUniqueId';

    /**
     * Indicates if encryptable card data is provided encrypted or as raw text.
     *
     * @return bool
     */
    public function getIsEncrypted();

    /**
     * @param bool $isEncrypted
     * @return self
     */
    public function setIsEncrypted($isEncrypted);

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
     * Either a tokenized or plain credit card number.
     *
     * xsd restrictions: 1-22 characters
     * @see get/setPanIsToken
     * @return string
     */
    public function getCardNumber();

    /**
     * @param string $ccNum
     * @return self
     */
    public function setCardNumber($ccNum);
}
