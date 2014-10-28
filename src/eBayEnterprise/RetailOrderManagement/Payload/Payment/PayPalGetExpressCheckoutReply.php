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

use eBayEnterprise\RetailOrderManagement\Payload;

class PayPalGetExpressCheckoutReply implements IPayPalGetExpressCheckoutReply
{
    const ADDRESS_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalAddress';
    const SUCCESS = 'Success';

    use TOrderId;
    use TPayPalValidators;
    use TShippingAddress;
    use TStrings;

    /** @var string **/
    protected $responseCode;
    /** @var string **/
    protected $payerEmail;
    /** @var string **/
    protected $payerId;
    /** @var string **/
    protected $payerStatus;
    /** @var string **/
    protected $payerNameHonorific;
    /** @var string **/
    protected $payerLastName;
    /** @var string **/
    protected $payerMiddleName;
    /** @var string **/
    protected $payerFirstName;
    /** @var string **/
    protected $payerCountry;
    /** @var string **/
    protected $payerPhone;
    /** @var IPayPalAddress **/
    protected $billingAddress;
    /** @var IPayPalAddress **/
    protected $shippingAddress;
    /** @var Payload\IPayloadFactory */
    protected $payloadFactory;
    /** @var Payload\IPayloadMap */
    protected $payloadMap;

    /** @var array */
    protected $extractionPaths = [
        'orderId' => 'string(x:OrderId)',
        'responseCode' => 'string(x:ResponseCode)',
        'payerEmail' => 'string(x:PayerEmail)',
        'payerId' => 'string(x:PayerId)',
        'payerStatus' => 'string(x:PayerStatus)',
        'payerNameHonorific' => 'string(x:PayerName/x:Honorific)',
        'payerLastName' => 'string(x:PayerName/x:LastName)',
        'payerMiddleName' => 'string(x:PayerName/x:MiddleName)',
        'payerFirstName' => 'string(x:PayerName/x:FirstName)',
        'payerCountry' => 'string(x:PayerCountry)',
        'payerPhone' => 'string(x:PayerPhone)',
    ];
    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;

    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator, Payload\IPayloadMap $payloadMap)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new Payload\PayloadFactory();
    }
    /**
     * Response code like Success, Failure etc
     *
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
     * Email address of the payer. Character length and limitations: 127 single-byte characters
     *
     * @return string
     */
    public function getPayerEmail()
    {
        return $this->payerEmail;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerEmail($email)
    {
        $this->payerEmail = $email;
        return $this;
    }
    /**
     * Unique identifier of the customer's PayPal account. Character length and limitations: 17 single-byte characters
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
     * Status of payer's email address.
     * "verified" or "unverified"
     *
     * @return string
     */
    public function getPayerStatus()
    {
        return $this->payerStatus;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerStatus($payerStatus)
    {
        $this->payerStatus = $payerStatus;
        return $this;
    }
    /**
     * A title you can assign to the payer. Typically "Dr.", "Mr.", "Ms." etc.
     *
     * @return string
     */
    public function getPayerNameHonorific()
    {
        return $this->payerNameHonorific;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerNameHonorific($hon)
    {
        $this->payerNameHonorific = $hon;
        return $this;
    }
    /**
     * The surname of the payer.
     *
     * @return string
     */
    public function getPayerLastName()
    {
        return $this->payerLastName;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerLastName($name)
    {
        $this->payerLastName = $name;
        return $this;
    }
    /**
     * The payer's middle name.
     *
     * @return string
     */
    public function getPayerMiddleName()
    {
        return $this->payerMiddleName;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerMiddleName($name)
    {
        $this->payerMiddleName = $name;
        return $this;
    }
    /**
     * The payer's first name.
     *
     * @return string
     */
    public function getPayerFirstName()
    {
        return $this->payerFirstName;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerFirstName($name)
    {
        $this->payerFirstName = $name;
        return $this;
    }
    /**
    * Payment sender's country of residence using standard two-character ISO 3166 country codes.
    * Character length and limitations: Two single-byte characters.
    *
    * @link http://countrycode.org/
    * @return string
    */
    public function getPayerCountry()
    {
        return $this->payerCountry;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerCountry($country)
    {
        $this->payerCountry = $country;
        return $this;
    }
    /**
     * Payer's phone on file with PayPal.
     *
     * @return string
     */
    public function getPayerPhone()
    {
        return $this->payerPhone;
    }
    /**
     * @param string
     * @return self
     */
    public function setPayerPhone($phone)
    {
        $this->payerPhone = $phone;
        return $this;
    }
    /**
     * build an empty IPayPalAddress instance
     * @return IPayPalAddress
     */
    public function getEmptyPayPalAddress()
    {
        return $this->payloadFactory->buildPayload(
            $this->payloadMap->getConcreteType(static::ADDRESS_INTERFACE),
            $this->payloadMap
        );
    }
    /**
     * Payer's business address on file with PayPal
     *
     * @return IPayPalAddress
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }
    /**
     *
     * @param  IPayPalAddress
     * @return self
     */
    public function setBillingAddress(IPayPalAddress $address)
    {
        $this->billingAddress = $address;
        return $this;
    }
    /**
     * Payer's shipping address on file with PayPal
     *
     * @return IPayPalAddress
     */
    public function getShippingAddress()
    {
        return $this->shippingAddress;
    }
    /**
     * @param IPayPalAddress
     * @return self
     */
    public function setShippingAddress(IPayPalAddress $address)
    {
        $this->shippingAddress = $address;
        return $this;
    }
    /**
     * Should downstream systems consider this reply a success?
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getResponseCode() === static::SUCCESS;
    }
    /**
     * Serialize a ResponseCode
     * @return string
     */
    protected function serializeResponseCode()
    {
        return "<ResponseCode>{$this->getResponseCode()}</ResponseCode>";
    }
    /**
     * Serialize a PayerEmail
     * @return string
     */
    protected function serializePayerEmail()
    {
        return "<PayerEmail>{$this->getPayerEmail()}</PayerEmail>";
    }
    /**
     * Serialize a PayerId
     * @return string
     */
    protected function serializePayerId()
    {
        return "<PayerId>{$this->getPayerId()}</PayerId>";
    }
    /**
     * Serialize a PayerStatus
     * @return string
     */
    protected function serializePayerStatus()
    {
        return "<PayerStatus>{$this->getPayerStatus()}</PayerStatus>";
    }
    /**
     * Serialize an Address
     * @return string
     */
    protected function serializeAddress($rootNode, IPayPalAddress $address=null)
    {
        return $address ? "<$rootNode>". $address->serialize() . "</$rootNode>" : '';
    }
    /**
     * Serialize Payer Name Details
     * @return string
     */
    protected function serializePayerName()
    {
        return "<PayerName>"
            . "<Honorific>{$this->getPayerNameHonorific()}</Honorific>"
            . "<LastName>{$this->getPayerLastName()}</LastName>"
            . "<MiddleName>{$this->getPayerMiddleName()}</MiddleName>"
            . "<FirstName>{$this->getPayerFirstName()}</FirstName>"
        . "</PayerName>";
    }
    /**
     * Serialize Payer Phone
     * @return string
     */
    protected function serializePayerPhone()
    {
        return "<PayerPhone>{$this->getPayerPhone()}</PayerPhone>";
    }
    /**
     * Serialize Payer Country
     * @return string
     */
    protected function serializePayerCountry()
    {
        return "<PayerCountry>{$this->getPayerCountry()}</PayerCountry>";
    }
    /**
     * Serialize a complete reply
     * @return string
     */
    public function serializeContents()
    {
        return
            $this->serializeOrderId()
            . $this->serializeResponseCode()
            . $this->serializePayerEmail()
            . $this->serializePayerId()
            . $this->serializePayerStatus()
            . $this->serializePayerName()
            . $this->serializePayerCountry()
            . $this->serializeAddress('BillingAddress', $this->getBillingAddress())
            . $this->serializePayerPhone()
            . $this->serializeAddress('ShippingAddress', $this->getShippingAddress());
    }
    /**
     * Return the string form of the payload data for transmission.
     * Validation is implied.
     *
     * @throws Exception\InvalidPayload
     * @return string
     */
    public function serialize()
    {
        // validate the payload data
        $this->validate();
        $xmlString = sprintf(
            '<%s xmlns="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->serializeContents()
        );
        $canonicalXml = $this->getPayloadAsDoc($xmlString)->C14N();
        $this->schemaValidate($canonicalXml);
        return $canonicalXml;
    }
    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $string
     * @return self
     */
    public function deserialize($string)
    {
        $this->schemaValidate($string);

        $xpath = $this->getPayloadAsXPath($string);
        foreach ($this->extractionPaths as $property => $path) {
            $this->$property = $xpath->evaluate($path);
        }
        $this->setBillingAddress($this->deserializeAddress('BillingAddress', $string));
        $this->setShippingAddress($this->deserializeAddress('ShippingAddress', $string));
        // payload is only valid of the unserialized data is also valid
        $this->validate();
        return $this;
    }
    /**
     * deserialize an address payload
     * @param  string $rootNode
     * @param  string $serializedMessage
     * @return IPayPalAddress
     */
    protected function deserializeAddress($rootNode, $serializedMessage)
    {
        $start = strpos($serializedMessage, "<$rootNode>");
        $end = strpos($serializedMessage, "</$rootNode>") + strlen("</$rootNode>");
        $payload = $this->getEmptyPayPalAddress();
        $payload->deserialize(substr($serializedMessage, $start, $end - $start));
        return $payload;
    }
    protected function getPayloadAsDoc($xmlString)
    {
        $d = new \DOMDocument();
        $d->loadXML($xmlString);
        return $d;
    }
    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new \DOMXPath($this->getPayloadAsDoc($xmlString));
        $xpath->registerNamespace('x', self::XML_NS);
        return $xpath;
    }
}
