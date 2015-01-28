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
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;

class PayPalDoExpressCheckoutRequest implements IPayPalDoExpressCheckoutRequest
{
    use TTopLevelPayload, TAmount, TOrderId, TCurrencyCode, TToken, TShippingAddress, TLineItemContainer;

    /** @var string* */
    protected $requestId;
    /** @var string * */
    protected $payerId;
    /** @var float * */
    protected $amount;
    /** @var string * */
    protected $pickUpStoreId;
    /** @var string * */
    protected $shipToName;
    /** @var mixed * */
    protected $shippingAddress;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        IPayload $parentPayload = null
    ) {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory();

        $this->extractionPaths = [
            'requestId' => 'string(@requestId)',
            'orderId' => 'string(x:OrderId)',
            'payerId' => 'string(x:PayerId)',
            'token' => 'string(x:Token)',
            'amount' => 'number(x:Amount)',
            'shipToName' => 'string(x:ShipToName)',
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
        $this->subpayloadExtractionPaths = [
            'lineItems' => "x:LineItems",
        ];
        $this->lineItems = $this->buildPayloadForInterface(static::ITERABLE_INTERFACE);
    }

    /**
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * xsd restrictions: 1-40 characters
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param string
     * @return self
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    protected function deserializeExtra()
    {
        if (count($this->getLineItems()) && $this->getLineItemsTotal() === null) {
            $this->calculateLineItemsTotal();
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
        . $this->serializeToken() // TToken
        . $this->serializePayerId()
        . $this->serializeCurrencyAmount('Amount', $this->getAmount(), $this->getCurrencyCode())
        . $this->serializePickupStoreId()
        . $this->serializeShipToName()
        . $this->serializeShippingAddress() // TShippingAddress
        . $this->serializeLineItemsContainer();
    }

    /**
     * Serialize the PayPalPayer Id
     * @return string
     */
    protected function serializePayerId()
    {
        return "<PayerId>{$this->getPayerId()}</PayerId>";
    }

    /**
     * Unique identifier of the customer's PayPal account, can be retrieved from the PayPalGetExpressCheckoutReply
     * or the URL the customer was redirected with from PayPal.
     *
     * @return string
     */
    public function getPayerId()
    {
        return $this->payerId;
    }

    /**
     * @param string
     * @return self
     */
    public function setPayerId($id)
    {
        $this->payerId = $id;
        return $this;
    }

    /**
     * The amount to authorize
     *
     * xsd note: minimum value 0
     *           maximum precision 2 decimal places
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
     * Serialize the PickupStoreId
     * @return string
     */
    protected function serializePickupStoreId()
    {
        return $this->nodeNullCoalesce("ShipToName", $this->getPickupStoreId());
    }

    /**
     * PickUpStoreId refers to store name/number for ship-to-store/in-store-pick up like "StoreName StoreNumber".
     * Optional except during ship-to-store delivery method.
     *
     * @return string
     */
    public function getPickUpStoreId()
    {
        return $this->pickUpStoreId;
    }

    /**
     * @param string
     * @return self
     */
    public function setPickUpStoreId($id)
    {
        $this->pickUpStoreId = $id;
        return $this;
    }

    /**
     * Serialize the Ship To Name
     * @return string
     */
    protected function serializeShipToName()
    {
        return $this->nodeNullCoalesce("ShipToName", $this->getShipToName());
    }

    /**
     * The name of the person shipped to like "FirsName LastName".
     *
     * @return string
     */
    public function getShipToName()
    {
        return $this->shipToName;
    }

    /**
     * @param string
     * @return self
     */
    public function setShipToName($name)
    {
        $this->shipToName = $name;
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
    /**
     * Name, value pairs of root attributes
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'requestId' => $this->getRequestId(),
        ];
    }
}
