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
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;

class PayPalSetExpressCheckoutRequest implements IPayPalSetExpressCheckoutRequest
{
    use TTopLevelPayload, TAmount, TOrderId, TCurrencyCode, TShippingAddress, TLineItemContainer;

    /** @var string * */
    protected $returnUrl;
    /** @var string * */
    protected $cancelUrl;
    /** @var string * */
    protected $localeCode;
    /** @var float * */
    protected $amount;
    /** @var boolean * */
    protected $addressOverride;

    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        ILineItemIterable $lineItems = null
    ) {
        $this->extractionPaths = [
            'orderId' => 'string(x:OrderId)',
            'amount' => 'number(x:Amount)',
            'returnUrl' => 'string(x:ReturnUrl)',
            'cancelUrl' => 'string(x:CancelUrl)',
            'localeCode' => 'string(x:LocaleCode)',
            'currencyCode' => 'string(x:Amount/@currencyCode)',
            // see addressLinesFromXPath - Address lines Line1 through Line4 are specially handled with that function
            'shipToCity' => 'string(x:ShippingAddress/x:City)',
            'shipToMainDivision' => 'string(x:ShippingAddress/x:MainDivision)',
            'shipToCountryCode' => 'string(x:ShippingAddress/x:CountryCode)',
            'shipToPostalCode' => 'string(x:ShippingAddress/x:PostalCode)',
            'shippingTotal' => 'number(x:LineItems/x:ShippingTotal)',
            'taxTotal' => 'number(x:LineItems/x:TaxTotal)',
            'lineItemsTotal' => 'number(x:LineItems/x:LineItemsTotal)',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'shipToLines',
                'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]",
            ]
        ];
        $this->booleanExtractionPaths = [
            'addressOverride' => 'string(x:AddressOverride)',
        ];
        $this->subpayloadExtractionPaths = [
            'lineItems' => "x:LineItems",
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->lineItems = $lineItems;
        if (is_null($this->lineItems)) {
            $payloadFactory = new PayloadFactory();
            $this->lineItems = $payloadFactory->buildPayload(
                $this->payloadMap->getConcreteType(static::ITERABLE_INTERFACE),
                $this->payloadMap
            );
        }
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeOrderId()
        . $this->serializeUrls()
        . $this->serializeLocaleCode()
        . $this->serializeCurrencyAmount('Amount', $this->getAmount(), $this->getCurrencyCode())
        . $this->serializeAddressOverride()
        . $this->serializeShippingAddress()
        . $this->serializeLineItemsContainer();
    }

    /**
     * Serialize the URLs to which PayPal should redirect upon return and cancel, respectively
     * @return string
     */
    protected function serializeUrls()
    {
        return "<ReturnUrl>{$this->getReturnUrl()}</ReturnUrl>"
        . "<CancelUrl>{$this->getCancelUrl()}</CancelUrl>";
    }

    /**
     * URL to which the customer's browser is returned after choosing to pay with PayPal.
     * PayPal recommends that the value be the final review page on which the customer confirms the order and payment.
     *
     * @return string
     */
    public function getReturnUrl()
    {
        return $this->returnUrl;
    }

    /**
     * @param string
     * @return self
     */
    public function setReturnUrl($url)
    {
        $this->returnUrl = $url;
        return $this;
    }

    /**
     * URL to which the customer is returned if the customer does not approve the use of PayPal.
     * PayPal recommends that the value be the original page on which the customer chose to pay with PayPal.
     *
     * @return string
     */
    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param string
     * @return self
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;
        return $this;
    }

    /**
     * Serialize the Local Code
     * @return string
     */
    protected function serializeLocaleCode()
    {
        return "<LocaleCode>{$this->getLocaleCode()}</LocaleCode>";
    }

    /**
     * Locale of pages displayed by PayPal during Express Checkout.
     *
     * @link https://developer.paypal.com/docs/classic/api/merchant/SetExpressCheckout_API_Operation_NVP/
     * @return string
     */
    public function getLocaleCode()
    {
        return $this->localeCode;
    }

    /**
     * @param string
     * @return self
     */
    public function setLocaleCode($localeCode)
    {
        $this->localeCode = $localeCode;
        return $this;
    }

    /**
     * The amount to authorize
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    protected function deserializeExtra()
    {
        if (count($this->getLineItems()) && $this->getLineItemsTotal() === null) {
            $this->calculateLineItemsTotal();
        }
    }

    /**
     * Serialize the AddressOverride indicator, which is a boolean
     * @return string
     */
    protected function serializeAddressOverride()
    {
        return '<AddressOverride>' . ($this->getAddressOverride() ? '1' : '0') . '</AddressOverride>';
    }

    /**
     * If true, PayPal will display the shipping address provided in the payload.
     * Otherwise PayPal will display whatever shipping address it has for the customer
     * and won't let the customer edit it.
     * Consider setting this flag implicitly based on whether or not an address is provided.
     * And simply implement the getter/setter to allow overriding as an edge case.
     *
     * @return bool
     */
    public function getAddressOverride()
    {
        return $this->addressOverride;
    }

    /**
     * @param bool
     * @return self
     */
    public function setAddressOverride($override)
    {
        $this->addressOverride = $override;
        return $this;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
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
     * @param string $nodeName
     * @param string $value
     * @return string
     */
    protected function nodeNullCoalesce($nodeName, $value)
    {
        if (!$value) {
            return '';
        }

        return sprintf('<%s>%s</%1$s>', $nodeName, $value);
    }
}
