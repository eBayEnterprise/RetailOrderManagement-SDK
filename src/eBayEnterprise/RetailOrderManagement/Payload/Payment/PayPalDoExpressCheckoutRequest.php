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

class PayPalDoExpressCheckoutRequest implements IPayPalDoExpressCheckoutRequest
{
    use TAmount;
    use TOrderId;
    use TPayPalCurrencyCode;
    use TPayPalToken;
    use TPayPalValidators;
    use TShippingAddress;
    use TStrings;

    const ITERABLE_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\ILineItemIterable';

    /** @var string**/
    protected $requestId;
    /** @var string **/
    protected $payerId;
    /** @var float **/
    protected $amount;
    /** @var string **/
    protected $pickUpStoreId;
    /** @var string **/
    protected $shipToName;
    /** @var mixed **/
    protected $shippingAddress;
    /** @var ILineItemContainer **/
    protected $lineItems;

    protected $nodesMap = [
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

    /** @var array */
    protected $addressLinesMap = [
        [
            'property' => 'shipToLines',
            'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]",
        ]
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
     * Whether the address was input on PayPal site or the merchant site, the final address
     * used should be passed at this time.
     *
     * @return IPhysicalAddress
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
    /**
     * @param IPhysicalAddress
     * @return self
     */
    public function setShippingAddress(IPhysicalAddress $address)
    {
        $this->shippingAddress = $address;
        return $this;
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
            '<%s xmlns="%s" requestId="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->getRequestId(),
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

        foreach ($this->nodesMap as $property => $xPath) {
            $this->$property = $domXPath->evaluate($xPath);
        }
        $this->addressLinesFromXPath($domXPath);
        $this->getLineItems()->deserialize($serializedPayload);
        $this->validate();
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
        . $this->serializeToken() // TPayPalToken
        . $this->serializePayerId()
        . $this->serializeCurrencyAmount('Amount', $this->getAmount(), $this->getCurrencyCode())
        . $this->serializePickupStoreId()
        . $this->serializeShipToName()
        . $this->serializeShippingAddress() // TShippingAddress
        . $this->serializeLineItems();
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
     * Serialize the PayPalPayer Id
     * @return string
     */
    protected function serializePayerId()
    {
        return "<ShipToName>{$this->getPayerId()}</ShipToName>";
    }
    /**
     * Serialize the Ship To Name
     * @return string
     */
    protected function serializeShipToName()
    {
        return "<ShipToName>{$this->getShipToName()}</ShipToName>";
    }
    /**
     * Serialize the PickupStoreId
     * @return string
     */
    protected function serializePickupStoreId()
    {
        return "<ShipToName>{$this->getPickupStoreId()}</ShipToName>";
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
