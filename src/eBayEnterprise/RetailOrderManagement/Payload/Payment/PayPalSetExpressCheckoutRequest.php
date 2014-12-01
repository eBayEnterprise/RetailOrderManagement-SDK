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
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;

class PayPalSetExpressCheckoutRequest implements IPayPalSetExpressCheckoutRequest
{
    use TAmount;
    use TOrderId;
    use TPayPalCurrencyCode;
    use TPayPalValidators;
    use TShippingAddress;
    use TStrings;

    const ITERABLE_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable';

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
    /** @var string * */
    protected $lineItems;

    /** @var array */
    protected $requiredNodesMap = [
        'orderId' => 'string(x:PaymentContext/x:OrderId)',
        'amount' => 'number(x:Amount)',
        'returnUrl' => 'string(x:ReturnUrl)',
        'cancelUrl' => 'string(x:CancelUrl)',
        'currencyCode' => 'string(x:Amount/@currencyCode)',
        // see addressLinesFromXPath - Address lines Line1 through Line4 are specially handled with that function
        'shipToCity' => 'string(x:ShippingAddress/x:City)',
        'shipToMainDivision' => 'string(x:ShippingAddress/x:MainDivision)',
        'shipToCountryCode' => 'string(x:ShippingAddress/x:CountryCode)',
        'shipToPostalCode' => 'string(x:ShippingAddress/x:PostalCode)',
    ];

    /** @var array */
    protected $addressLinesMap = [
        [
            'property' => 'shipToLines',
            'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]",
        ]
    ];
    /** @var array property/XPath pairs that take boolean values */
    protected $booleanXPaths = [
        'addressOverride' => 'string(x:AddressOverride)',
    ];

    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;

    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap
    ) {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $payloadFactory = new PayloadFactory();
        $this->lineItems = $payloadFactory->buildPayload(
            $payloadMap->getConcreteType(static::ITERABLE_INTERFACE),
            $payloadMap
        );
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

    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $serializedPayload
     * @return self
     */
    public function deserialize($serializedPayload)
    {
        $this->schemaValidate($serializedPayload);
        $dom = new \DomDocument();
        $dom->loadXML($serializedPayload);
        $domXPath = new \DOMXPath($dom);
        $domXPath->registerNamespace('x', self::XML_NS);

        foreach ($this->requiredNodesMap as $property => $xPath) {
            $this->$property = $domXPath->evaluate($xPath);
        }
        $this->addressLinesFromXPath($domXPath);
        foreach ($this->booleanXPaths as $property => $xPath) {
            $value = $domXPath->evaluate($xPath);
            $this->$property = $this->booleanFromString($value);
        }
        $this->getLineItems()->deserialize($serializedPayload);

        $this->validate();
        return $this;
    }

    /**
     * Get an iterable of the line items for this container.
     *
     * @return ILineItemIterable
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @param ILineItemIterable
     * @return self
     */
    public function setLineItems(ILineItemIterable $items)
    {
        $this->lineItems = $items;
        return $this;
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
        . $this->serializeLineItems();
    }

    /**
     * Serialize the payload into XML
     *
     * @throws Exception\InvalidPayload
     * @return string
     */
    public function serialize()
    {
        // make sure this payload is valid first
        $this->validate();

        $xmlString = sprintf(
            '<%s xmlns="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->serializeContents()
        );

        // validate the xML we just created
        $doc = new \DOMDocument();
        $doc->loadXML($xmlString);
        $xml = $doc->C14N();

        $this->schemaValidate($xml);
        return $xml;
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
     * Serialize the Local Code
     * @return string
     */
    protected function serializeLocaleCode()
    {
        return "<LocaleCode>{$this->getLocaleCode()}</LocaleCode>";
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
     * Serialization of line items
     * @return string
     */
    protected function serializeLineItems()
    {
        return $this->getLineItems()->serialize();
    }

    /**
     * Get Line1 through Line4 for an Address
     * Find all of the nodes in the address node that
     * start with 'Line' and add their value to the
     * proper address lines array
     *
     * @param \DOMXPath $domXPath
     */
    protected function addressLinesFromXPath(\DOMXPath $domXPath)
    {
        foreach ($this->addressLinesMap as $address) {
            $lines = $domXPath->query($address['xPath']);
            $property = $address['property'];
            $this->$property = [];
            foreach ($lines as $line) {
                array_push($this->$property, $line->nodeValue);
            }
        }
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
    }
}
