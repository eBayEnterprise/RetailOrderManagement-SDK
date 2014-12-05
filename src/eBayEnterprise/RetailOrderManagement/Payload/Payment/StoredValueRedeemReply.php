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

use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;

/**
 * Class StoredValueRedeemReply
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
class StoredValueRedeemReply implements IStoredValueRedeemReply
{
    use TTopLevelPayload, TPaymentContext;

    /** @var string */
    protected $pin;
    /** @var float */
    protected $amountRedeemed;
    /** @var string */
    protected $amountRedeemedCurrencyCode;
    /** @var float */
    protected $balanceAmount;
    /** @var string */
    protected $balanceAmountCurrencyCode;
    /** @var string */
    protected $responseCode;
    /** @var array response codes that are considered a success */
    protected $successResponseCodes = ['Success'];

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'orderId' => 'string(x:PaymentContext/x:OrderId)',
            'cardNumber' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
            'responseCode' => 'string(x:ResponseCode)',
            'amountRedeemed' => 'number(x:AmountRedeemed)',
            'amountRedeemedCurrencyCode' => 'string(x:AmountRedeemed/@currencyCode)',
            'balanceAmount' => 'number(x:BalanceAmount)',
            'balanceAmountCurrencyCode' => 'string(x:BalanceAmount/@currencyCode)',
        ];
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)',
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    public function getAmountRedeemed()
    {
        return $this->amountRedeemed;
    }

    public function setAmountRedeemed($amount)
    {
        if (is_float($amount)) {
            $this->amountRedeemed = round($amount, 2, PHP_ROUND_HALF_UP);
        } else {
            $this->amountRedeemed = null;
        }
        return $this;
    }

    public function getAmountRedeemedCurrencyCode()
    {
        return $this->amountRedeemedCurrencyCode;
    }

    public function setAmountRedeemedCurrencyCode($code)
    {
        $value = null;

        $cleaned = $this->cleanString($code, 3);
        if ($cleaned !== null) {
            if (!strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->amountRedeemedCurrencyCode = $value;

        return $this;
    }

    public function getBalanceAmount()
    {
        return $this->balanceAmount;
    }

    public function setBalanceAmount($amount)
    {
        if (is_float($amount)) {
            $this->balanceAmount = round($amount, 2, PHP_ROUND_HALF_UP);
        } else {
            $this->balanceAmount = null;
        }
        return $this;
    }

    public function getBalanceAmountCurrencyCode()
    {
        return $this->balanceAmountCurrencyCode;
    }

    public function setBalanceAmountCurrencyCode($code)
    {
        $value = null;

        $cleaned = $this->cleanString($code, 3);
        if ($cleaned !== null) {
            if (!strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->balanceAmountCurrencyCode = $value;

        return $this;
    }

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getCurrencyCodeRedeemed()
    {
        return $this->amountRedeemedCurrencyCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCodeRedeemed($code)
    {
        $this->amountRedeemedCurrencyCode = $code;
        return $this;
    }

    /**
     * The 3-character ISO 4217 code that represents
     * the type of currency being used for a transaction.
     *
     * @link http://www.iso.org/iso/home/standards/currency_codes.htm
     * @return string
     */
    public function getBalanceCurrencyCode()
    {
        return $this->balanceAmountCurrencyCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setBalanceCurrencyCode($code)
    {
        $this->balanceAmountCurrencyCode = $code;
        return $this;
    }

    /**
     * Whether the gift card was successfully redeemed.
     * @return bool
     */
    public function wasRedeemed()
    {
        return in_array($this->getResponseCode(), $this->successResponseCodes, true);
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentContext()
        . $this->serializeResponseCode()
        . $this->serializeAmounts('AmountRedeemed')
        . $this->serializeAmounts('BalanceAmount');
    }

    /**
     * Build the response code node
     * @return string
     */
    protected function serializeResponseCode()
    {
        return "<ResponseCode>{$this->getResponseCode()}</ResponseCode>";
    }

    /**
     * The result of the request transaction.
     *
     * xsd note: possible values: Fail, Success, Timeout
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setResponseCode($code)
    {
        $this->responseCode = $code;
        return $this;
    }

    /**
     * Build the Amount node
     * @param string $amountType either 'AmountRedeemed' or 'BalanceAmount'
     * @return string
     */
    protected function serializeAmounts($amountType)
    {
        $getVal = "get{$amountType}";
        $getCurCode = "{$getVal}CurrencyCode";
        return sprintf(
            '<%s currencyCode="%s">%1.02F</%1$s>',
            $amountType,
            $this->{$getCurCode}(),
            $this->{$getVal}()
        );
    }

    /**
     * Build the Pin node
     *
     * @return string
     */
    protected function serializePin()
    {
        return "<Pin>{$this->getPin()}</Pin>";
    }

    public function getPin()
    {
        return $this->pin;
    }

    public function setPin($pin)
    {
        $this->pin = $this->cleanString($pin, 8);
        return $this;
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . static::XSD;
    }

    /**
     * Return the name of the xml root node.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * The XML namespace for the payload.
     *
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
