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

use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;

class CreditCardAuthReply implements ICreditCardAuthReply
{
    const ROOT_NODE = 'CreditCardAuthReply';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
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
    /** @var string */
    protected $phoneResponseCode;
    /** @var string */
    protected $nameResponseCode;
    /** @var string */
    protected $emailResponseCode;
    /** @var float **/
    protected $amountAuthorized;
    /** @var string **/
    protected $currencyCode;
    /** @var IValidatorIterator */
    protected $validators;

    public function __construct(IValidatorIterator $validators)
    {
        $this->validators = $validators;
    }

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

    public function getPhoneResponseCode()
    {
        return $this->phoneResponseCode;
    }

    public function getNameResponseCode()
    {
        return $this->nameResponseCode;
    }

    public function getEmailResponseCode()
    {
        return $this->emailResponseCode;
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

    public function getResponseCode()
    {

    }

    /**
     * Serialize the data into a string of XML.
     * @return string
     */
    public function serialize()
    {
        $xmlString = sprintf(
            '<%s xmlns="%s">%s</%1$s>',
            self::ROOT_NODE, self::XML_NS, $this->serializeContents()
        );
        $doc = new \DOMDocument();
        $doc->loadXML($xmlString);
        return $doc->saveXML();
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentContext()
            . $this->serializeResponseCodes()
            . $this->serializeAdditionalResponseCodes()
            . $this->serializeAmount();
    }

    /**
     * Create an XML string representing the PaymentContext nodes
     * @return string
     */
    protected function serializePaymentContext()
    {
        return sprintf(
            '<PaymentContext><OrderId>%s</OrderId><PaymentAccountUniqueId isToken="%s">%s</PaymentAccountUniqueId></PaymentContext>',
            $this->getOrderId(), $this->getPanIsToken() ? 'true' : 'false', $this->getPaymentAccountUniqueId()
        );
    }

    /**
     * Create an XML string representing the various response codes, e.g.
     * AuthorizationResponseCode, BankAuthorizationCode, CVV2ResponseCode, etc.
     * @return string
     */
    protected function serializeResponseCodes()
    {
        return sprintf(
            '<AuthorizationResponseCode>%s</AuthorizationResponseCode><BankAuthorizationCode>%s</BankAuthorizationCode><CVV2ResponseCode>%s</CVV2ResponseCode><AVSResponseCode>%s</AVSResponseCode>',
            $this->getAuthorizationResponseCode(), $this->getBankAuthorizationCode(), $this->getCVV2ResponseCode(), $this->getAVSResponseCode()
        );
    }
    /**
     * Create an XML string representing any of the optional response codes,
     * e.g. EmailResponseCode, PhoneResponseCode, etc.
     * @return string
     */
    protected function serializeAdditionalResponseCodes()
    {
        $phoneResponseCode = $this->getPhoneResponseCode();
        $nameResponseCode = $this->getNameResponseCode();
        $emailResponseCode = $this->getEmailResponseCode();
        return ($phoneResponseCode ? "<PhoneResponseCode>{$phoneResponseCode}</PhoneResponseCode>" : '')
            . ($nameResponseCode ? "<NameResponseCode>{$nameResponseCode}</NameResponseCode>" : '')
            . ($emailResponseCode ? "<EmailResponseCode>{$emailResponseCode}</EmailResponseCode>" : '');
    }
    /**
     * Create an XML string representing the amount authorized.
     * @return string
     */
    protected function serializeAmount()
    {
        // make sure the payload is valid before attempting to serialize
        $this->validate();
        return sprintf(
            '<AmountAuthorized currencyCode="%s">%01.2F</AmountAuthorized>',
            $this->getCurrencyCode(), $this->getAmountAuthorized()
        );
    }

    public function deserialize($string)
    {

    }

    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }
        return $this;
    }
}
