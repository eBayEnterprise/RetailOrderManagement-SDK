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
use eBayEnterprise\RetailOrderManagement\Payload\Exception;

class PayPalGetExpressCheckoutReply implements IPayPalGetExpressCheckoutReply
{
    const ADDRESS_INTERFACE = '\eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalAddress';
    const SUCCESS = 'Success';

    use Payload\TTopLevelPayload, TOrderId, TBillingAddress, TShippingAddress;

    /** @var string * */
    protected $responseCode;
    /** @var string * */
    protected $payerEmail;
    /** @var string * */
    protected $payerId;
    /** @var string * */
    protected $payerStatus;
    /** @var string * */
    protected $payerNameHonorific;
    /** @var string * */
    protected $payerLastName;
    /** @var string * */
    protected $payerMiddleName;
    /** @var string * */
    protected $payerFirstName;
    /** @var string * */
    protected $payerCountry;
    /** @var string * */
    protected $payerPhone;
    /** @var Payload\IPayloadFactory */
    protected $payloadFactory;
    /** @var Payload\IPayloadMap */
    protected $payloadMap;

    public function __construct(
        Payload\IValidatorIterator $validators,
        Payload\ISchemaValidator $schemaValidator,
        Payload\IPayloadMap $payloadMap
    ) {
        $this->extractionPaths = [
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
            'billingCity' => 'string(x:BillingAddress/x:City)',
            'billingCountryCode' => 'string(x:BillingAddress/x:CountryCode)',
            'shipToCity' => 'string(x:ShippingAddress/x:City)',
            'shipToCountryCode' => 'string(x:ShippingAddress/x:CountryCode)',
        ];
        $this->optionalExtractionPaths = [
            'billingMainDivision' => 'x:BillingAddress/x:MainDivision',
            'billingPostalCode' => 'x:BillingAddress/x:PostalCode',
            'shipToMainDivision' => 'x:ShippingAddress/x:MainDivision',
            'shipToPostalCode' => 'x:ShippingAddress/x:PostalCode',
        ];
        $this->addressLinesExtractionMap = [
            [
                'property' => 'billingLines',
                'xPath' => "x:BillingAddress/*[starts-with(name(), 'Line')]"
            ],
            [
                'property' => 'shipToLines',
                'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]"
            ]
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new Payload\PayloadFactory();
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
     * Serialize a complete reply
     * @return string
     */
    public function serializeContents()
    {
        return $this->serializeOrderId()
        . $this->serializeResponseCode()
        . $this->serializePayerEmail()
        . $this->serializePayerId()
        . $this->serializePayerStatus()
        . $this->serializePayerName()
        . $this->serializePayerCountry()
        . $this->serializeBillingAddress()
        . $this->serializePayerPhone()
        . $this->serializeShippingAddress();
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
     * Serialize a PayerId
     * @return string
     */
    protected function serializePayerId()
    {
        return "<PayerId>{$this->getPayerId()}</PayerId>";
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
     * Serialize a PayerStatus
     * @return string
     */
    protected function serializePayerStatus()
    {
        return "<PayerStatus>{$this->getPayerStatus()}</PayerStatus>";
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
     * Serialize Payer Country
     * @return string
     */
    protected function serializePayerCountry()
    {
        return "<PayerCountry>{$this->getPayerCountry()}</PayerCountry>";
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
     * Serialize Payer Phone
     * @return string
     */
    protected function serializePayerPhone()
    {
        return "<PayerPhone>{$this->getPayerPhone()}</PayerPhone>";
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
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
    }

    /**
     * Return a serialized XML node if it has a value, empty string otherwise.
     *
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
}
