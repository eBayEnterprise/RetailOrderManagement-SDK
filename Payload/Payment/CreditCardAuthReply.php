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

class CreditCardAuthRequest implements ICreditCardAuthRequest
{
    /** @var string **/
    protected $orderId;
    /** @var string **/
    protected $paymentAccountUniqueId;
    /** @var bool **/
    protected $panIsToken;
    /** @var string **/
    protected $authorizationResponseCode;
    /** @var string **/
    protected $bankAuthorizationCode;
    /** @var string **/
    protected $cvv2ResponseCode;
    /** @var string **/
    protected $avsResponseCode;
    /** @var float **/
    protected $amountAuthorized;
    /** @var string **/
    protected $currencyCode;

    public function getOrderId()
    {
        return $this->orderId;
    }
    public function getPaymentAccountUniqueId()
    {
        return $this->paymentAccountUniqueId;
    }
    public function getPanIsToken()
    {
        return $this->panIsToken;
    }
    public function getAuthorizationResponseCode()
    {
        return $this->authorizationResponseCode;
    }
    public function getBankAuthorizationCode()
    {
        return $this->bankAuthorizationCode;
    }
    public function getCVV2ResponseCode()
    {
        return $this->cvv2ResponseCode;
    }
    public function getAVSResponseCode()
    {
        return $this->avsResponseCode;
    }
    public function getAmountAuthorized()
    {
        return $this->amountAuthorized;
    }
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }
    public function getIsAuthSuccessful()
    {

    }
    public function getIsAuthAcceptable()
    {

    }
    public function getResponseCode($value='')
    {

    }
}
