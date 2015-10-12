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
 * trait TPaymentAccountUniqueId
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 * @example <PaymentAccountUniqueId isToken="true">{card number}</PaymentAccountUniqueId>
 */
trait TPaymentAccountUniqueId
{
    /** @var bool */
    protected $isEncrypted = false;
    /** @var bool */
    protected $panIsToken;
    /** @var string */
    protected $cardNumber;

    /**
     * XML serialized PaymentAccountUniqueId node
     * @return string
     */
    protected function serializePaymentAccountUniqueId()
    {
        $cardNumberNode = $this->getIsEncrypted()
            ? IPaymentAccountUniqueId::ENCRYPTED_CARD_NUMBER_NODE
            : IPaymentAccountUniqueId::RAW_CARD_NUMBER_NODE;
        return sprintf(
            '<%1$s %2$s>%3$s</%1$s>',
            $cardNumberNode,
            $this->serializeIsToken(),
            $this->xmlEncode($this->getCardNumber())
        );
    }

    /**
     * If panIsToken, the string 'true', otherwise the string 'false'.
     * @return string
     */
    protected function serializeIsToken()
    {
        return $this->getIsEncrypted() ? '' : sprintf('isToken="%s"', ($this->getPanIsToken() ? 'true' : 'false'));
    }

    public function getPanIsToken()
    {
        return $this->panIsToken;
    }

    public function setPanIsToken($isToken)
    {
        $this->panIsToken = is_bool($isToken) ? $isToken : null;
        return $this;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function setCardNumber($ccNum)
    {
        $this->cardNumber = $this->getIsEncrypted() ? $this->cleanString($ccNum, 1000) : $this->cleanString($ccNum, 22);
        return $this;
    }

    public function getIsEncrypted()
    {
        return $this->isEncrypted;
    }

    public function setIsEncrypted($isEncrypted)
    {
        $this->isEncrypted = (bool)$isEncrypted;
        return $this;
    }

    /**
     * Trim any white space and return the resulting string truncating to $maxLength.
     *
     * Return null if the result is an empty string or not a string
     *
     * @param string $string
     * @param int $maxLength
     * @return string or null
     */
    abstract protected function cleanString($string, $maxLength);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
