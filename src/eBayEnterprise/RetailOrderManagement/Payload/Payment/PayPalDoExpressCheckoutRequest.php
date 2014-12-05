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

class PayPalDoExpressCheckoutRequest implements IPayPalDoExpressCheckoutRequest
{
    use TTopLevelPayload, TAmount, TOrderId, TCurrencyCode, TToken, TShippingAddress;

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
    /** @var ILineItemContainer * */
    protected $lineItems;

    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap
    ) {
        $this->extractionPaths = [
            'requestId' => 'string(@requestId)',
            'orderId' => 'string(x:PaymentContext/x:OrderId)',
            'amount' => 'number(x:Amount)',
            'shipToName' => 'string(x:ShipToName)',
            // see addressLinesFromXPath - Address lines Line1 through Line4 are specially handled with that function
            'shipToCity' => 'string(x:ShippingAddress/x:City)',
            'shipToMainDivision' => 'string(x:ShippingAddress/x:MainDivision)',
            'shipToCountryCode' => 'string(x:ShippingAddress/x:CountryCode)',
            'shipToPostalCode' => 'string(x:ShippingAddress/x:PostalCode)',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'shipToLines',
                'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]",
            ]
        ];
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
        . $this->serializeLineItems();
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

    /**
     * Serialization of line items
     * @return string
     */
    protected function serializeLineItems()
    {
        return $this->getLineItems()->serialize();
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
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
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
