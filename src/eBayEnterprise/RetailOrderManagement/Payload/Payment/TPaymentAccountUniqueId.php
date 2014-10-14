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

trait TPaymentAccountUniqueId {
    /** @var bool **/
    protected $panIsToken;
    /** @var string **/
    protected $paymentAccountUniqueId;

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
        return $this->paymentAccountUniqueId;
    }

    public function setCardNumber($ccNum)
    {
        $this->paymentAccountUniqueId = $this->cleanString($ccNum, 22);
        return $this;
    }

    /**
     * XML serialized PaymentAccountUniqueId node
     * @return string
     */
    protected function serializePaymentAccountUniqueId()
    {
        return sprintf(
            '<PaymentAccountUniqueId isToken="%s">%s</PaymentAccountUniqueId>',
            $this->serializeIsToken(),
            $this->getCardNumber()
        );
    }

    /**
     * If panIsToken, the string 'true', otherwise the string 'false'.
     * @return string
     */
    protected function serializeIsToken()
    {
        return $this->getPanIsToken() ? 'true' : 'false';
    }
}