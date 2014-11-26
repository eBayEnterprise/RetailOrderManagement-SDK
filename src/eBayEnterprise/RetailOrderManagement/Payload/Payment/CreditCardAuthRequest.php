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

use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

class CreditCardAuthRequest implements ICreditCardAuthRequest
{
    use TPayload, TPaymentContext;

    /** @var string */
    protected $requestId;
    /** @var \DateTime */
    protected $expirationDate;
    /** @var string */
    protected $cardSecurityCode;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $currencyCode;
    /** @var string */
    protected $billingFirstName;
    /** @var string */
    protected $billingLastName;
    /** @var string */
    protected $billingPhone;
    /** @var array */
    protected $billingLines;
    /** @var string */
    protected $billingCity;
    /** @var string */
    protected $billingMainDivision;
    /** @var string */
    protected $billingCountryCode;
    /** @var string */
    protected $billingPostalCode;
    /** @var string */
    protected $customerEmail;
    /** @var string */
    protected $customerIpAddress;
    /** @var string */
    protected $shipToFirstName;
    /** @var string */
    protected $shipToLastName;
    /** @var string */
    protected $shipToPhone;
    /** @var array */
    protected $shipToLines;
    /** @var string */
    protected $shipToCity;
    /** @var string */
    protected $shipToMainDivision;
    /** @var string */
    protected $shipToCountryCode;
    /** @var string */
    protected $shipToPostalCode;
    /** @var bool */
    protected $isRequestToCorrectCVVOrAVSError;
    /** @var string */
    protected $authenticationAvailable;
    /** @var string */
    protected $authenticationStatus;
    /** @var string */
    protected $cavvUcaf;
    /** @var string */
    protected $transactionId;
    /** @var string */
    protected $eci;
    /** @var string */
    protected $payerAuthenticationResponse;
    /** @var array[] */
    protected $addressLinesMap;

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'requestId' => 'string(@requestId)',
            'orderId' => 'string(x:PaymentContext/x:OrderId)',
            'cardNumber' =>
                'string(x:PaymentContext/x:EncryptedPaymentAccountUniqueId|x:PaymentContext/x:PaymentAccountUniqueId)',
            'expirationDate' => 'string(x:ExpirationDate)',
            'cardSecurityCode' => 'string(x:CardSecurityCode|x:EncryptedCardSecurityCode)',
            'amount' => 'number(x:Amount)',
            'currencyCode' => 'string(x:Amount/@currencyCode)',
            'billingFirstName' => 'string(x:BillingFirstName)',
            'billingLastName' => 'string(x:BillingLastName)',
            'billingPhone' => 'string(x:BillingPhoneNo)',
            'billingCity' => 'string(x:BillingAddress/x:City)',
            'billingCountryCode' => 'string(x:BillingAddress/x:CountryCode)',
            'customerEmail' => 'string(x:CustomerEmail)',
            'customerIpAddress' => 'string(x:CustomerIPAddress)',
            'shipToFirstName' => 'string(x:ShipToFirstName)',
            'shipToLastName' => 'string(x:ShipToLastName)',
            'shipToPhone' => 'string(x:ShipToPhoneNo)',
            'shipToCity' => 'string(x:ShippingAddress/x:City)',
            'shipToCountryCode' => 'string(x:ShippingAddress/x:CountryCode)',
            'isRequestToCorrectCVVOrAVSError' => 'boolean(x:isRequestToCorrectCVVOrAVSError)',
            'isEncrypted' => 'boolean(x:PaymentContext/x:EncryptedPaymentAccountUniqueId)',
        ];
        $this->addressLinesMap = [
            [
                'property' => 'billingLines',
                'xPath' => "x:BillingAddress/*[starts-with(name(), 'Line')]"
            ],
            [
                'property' => 'shipToLines',
                'xPath' => "x:ShippingAddress/*[starts-with(name(), 'Line')]"
            ]
        ];
        $this->optionalExtractionPaths = [
            'billingMainDivision' => 'x:BillingAddress/x:MainDivision',
            'billingPostalCode' => 'x:BillingAddress/x:PostalCode',
            'shipToMainDivision' => 'x:ShippingAddress/x:MainDivision',
            'shipToPostalCode' => 'x:ShippingAddress/x:PostalCode',
            'authenticationAvailable' => 'x:SecureVerificationData/x:AuthenticationAvailable',
            'authenticationStatus' => 'x:SecureVerificationData/x:AuthenticationStatus',
            'cavvUcaf' => 'x:SecureVerificationData/x:CavvUcaf',
            'transactionId' => 'x:SecureVerificationData/x:TransactionId',
            'payerAuthenticationResponse' => 'x:SecureVerificationData/x:PayerAuthenticationResponse',
            'eci' => 'x:SecureVerificationData/x:ECI',
        ];
        $this->booleanExtractionPaths = [
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)',
            'isRequestToCorrectCVVOrAVSError' => 'string(x:isRequestToCorrectCVVOrAVSError)'
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    public function getRequestId()
    {
        return $this->requestId;
    }

    public function setRequestId($requestId)
    {
        $this->requestId = $this->cleanString($requestId, 40);
        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(\DateTime $date)
    {
        $month = $date->format('j');
        $year = $date->format('Y');
        $this->expirationDate = checkdate($month, 1, $year) ? $date->format('Y-m') : null;
        return $this;
    }

    public function getCardSecurityCode()
    {
        return $this->cardSecurityCode;
    }

    public function setCardSecurityCode($cvv)
    {
        if ($this->getIsEncrypted()) {
            $this->cardSecurityCode = $this->cleanString($cvv, 1000);
        } else {
            $cleaned = $this->cleanString($cvv, 4);
            $this->cardSecurityCode = preg_match('#^\d{3,4}$#', $cleaned) ? $cleaned : null;
        }
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        if (is_float($amount)) {
            $this->amount = round($amount, 2, PHP_ROUND_HALF_UP);
        } else {
            $this->amount = null;
        }
        return $this;
    }

    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    public function setCurrencyCode($code)
    {
        $value = null;

        $cleaned = $this->cleanString($code, 3);
        if ($cleaned !== null) {
            if (!strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->currencyCode = $value;

        return $this;
    }

    public function getEmail()
    {
        return $this->customerEmail;
    }

    public function setEmail($email)
    {
        $value = null;
        $cleaned = $this->cleanString($email, 70);
        if ($cleaned !== null) {
            $match = filter_var($cleaned, FILTER_VALIDATE_EMAIL);
            if ($match) {
                $value = $cleaned;
            }
        }
        $this->customerEmail = $value;

        return $this;
    }

    public function getIp()
    {
        return $this->customerIpAddress;
    }

    public function setIp($ip)
    {
        $pattern = '/((25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)\.){3}(25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)/';
        $value = null;

        $cleaned = $this->cleanString($ip, 70);
        if ($cleaned !== null) {
            $match = preg_match($pattern, $cleaned);

            if ($match === 1) {
                $value = $cleaned;
            }
        }
        $this->customerIpAddress = $value;

        return $this;
    }

    public function getBillingFirstName()
    {
        return $this->billingFirstName;
    }

    public function setBillingFirstName($name)
    {
        $value = null;

        if (is_string($name)) {
            $trimmed = trim($name);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->billingFirstName = $value;

        return $this;
    }

    public function getBillingLastName()
    {
        return $this->billingLastName;
    }

    public function setBillingLastName($name)
    {
        $value = null;

        if (is_string($name)) {
            $trimmed = trim($name);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->billingLastName = $value;

        return $this;
    }

    public function getBillingPhone()
    {
        return $this->billingPhone;
    }

    public function setBillingPhone($phone)
    {
        $value = null;

        if (is_string($phone)) {
            $trimmed = trim($phone);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->billingPhone = $value;

        return $this;
    }

    public function getBillingLines()
    {
        return is_array($this->billingLines) ? implode("\n", $this->billingLines) : null;
    }

    public function setBillingLines($lines)
    {
        $this->billingLines = $this->cleanAddressLines($lines);
        return $this;
    }

    public function getBillingCity()
    {
        return $this->billingCity;
    }

    public function setBillingCity($city)
    {
        $this->billingCity = $this->cleanString($city, 35);
        return $this;
    }

    public function getBillingMainDivision()
    {
        return $this->billingMainDivision;
    }

    public function setBillingMainDivision($div)
    {
        $this->billingMainDivision = $this->cleanString($div, 35);
        return $this;
    }

    public function getBillingCountryCode()
    {
        return $this->billingCountryCode;
    }

    public function setBillingCountryCode($code)
    {
        $cleaned = $this->cleanString($code, 40);
        $this->billingCountryCode = strlen($cleaned) >= 2 ? $cleaned : null;
        return $this;
    }

    public function getBillingPostalCode()
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode($code)
    {
        $this->billingPostalCode = $this->cleanString($code, 15);
        return $this;
    }

    public function getShipToFirstName()
    {
        return $this->shipToFirstName;
    }

    public function setShipToFirstName($name)
    {
        $value = null;

        if (is_string($name)) {
            $trimmed = trim($name);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->shipToFirstName = $value;

        return $this;
    }

    public function getShipToLastName()
    {
        return $this->shipToLastName;
    }

    public function setShipToLastName($name)
    {
        $value = null;

        if (is_string($name)) {
            $trimmed = trim($name);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->shipToLastName = $value;

        return $this;
    }

    public function getShipToPhone()
    {
        return $this->shipToPhone;
    }

    public function setShipToPhone($phone)
    {
        $value = null;

        if (is_string($phone)) {
            $trimmed = trim($phone);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->shipToPhone = $value;

        return $this;
    }

    public function getShipToLines()
    {
        return is_array($this->shipToLines) ? implode("\n", $this->shipToLines) : null;
    }

    public function setShipToLines($lines)
    {
        $this->shipToLines = $this->cleanAddressLines($lines);
        return $this;
    }

    public function getShipToCity()
    {
        return $this->shipToCity;
    }

    public function setShipToCity($city)
    {
        $this->shipToCity = $this->cleanString($city, 35);
        return $this;
    }

    public function getShipToMainDivision()
    {
        return $this->shipToMainDivision;
    }

    public function setShipToMainDivision($div)
    {
        $this->shipToMainDivision = $this->cleanString($div, 35);
        return $this;
    }

    public function getShipToCountryCode()
    {
        return $this->shipToCountryCode;
    }

    public function setShipToCountryCode($code)
    {
        $cleaned = $this->cleanString($code, 40);
        $this->shipToCountryCode = strlen($cleaned) >= 2 ? $cleaned : null;
        return $this;
    }

    public function getShipToPostalCode()
    {
        return $this->shipToPostalCode;
    }

    public function setShipToPostalCode($code)
    {
        $this->shipToPostalCode = $this->cleanString($code, 15);
        return $this;
    }

    public function getIsRequestToCorrectCvvOrAvsError()
    {
        return $this->isRequestToCorrectCVVOrAVSError;
    }

    public function setIsRequestToCorrectCvvOrAvsError($flag)
    {
        $this->isRequestToCorrectCVVOrAVSError = is_bool($flag) ? $flag : null;
        return $this;
    }

    public function getAuthenticationAvailable()
    {
        return $this->authenticationAvailable;
    }

    public function setAuthenticationAvailable($token)
    {
        $value = null;

        $cleaned = $this->cleanString($token, 1);
        if ($cleaned !== null) {
            $cleaned = strtoupper($cleaned);
            if (strstr('YNU', $cleaned)) {
                $value = $cleaned;
            }
        }
        $this->authenticationAvailable = $value;

        return $this;
    }

    public function getAuthenticationStatus()
    {
        return $this->authenticationStatus;
    }

    public function setAuthenticationStatus($token)
    {
        $value = null;

        $cleaned = $this->cleanString($token, 1);
        if ($cleaned !== null) {
            $cleaned = strtoupper($cleaned);
            if (strstr('YNUA', $cleaned)) {
                $value = $cleaned;
            }
        }
        $this->authenticationStatus = $value;

        return $this;
    }

    public function getCavvUcaf()
    {
        return $this->cavvUcaf;
    }

    public function setCavvUcaf($data)
    {
        $this->cavvUcaf = $this->cleanString($data, 64);
        return $this;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($id)
    {
        $this->transactionId = $this->cleanString($id, 64);
        return $this;
    }

    public function getEci()
    {
        return $this->eci;
    }

    public function setEci($eci)
    {
        $value = null;

        if (is_string($eci)) {
            $trimmed = trim($eci);
            if (!empty($trimmed)) {
                $value = $trimmed;
            }
        }
        $this->eci = $value;

        return $this;
    }

    public function getPayerAuthenticationResponse()
    {
        return $this->payerAuthenticationResponse;
    }

    public function setPayerAuthenticationResponse($response)
    {
        $this->payerAuthenticationResponse = $this->cleanString($response, 10000);
        return $this;
    }

    /**
     * Serialize the various parts of the payload into XML strings and
     * simply concatenate them together.
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializePaymentContext()
        . $this->serializeCardInfo()
        . $this->serializeBillingNamePhone()
        . $this->serializeBillingAddress()
        . $this->serializeCustomerInfo()
        . $this->serializeShippingNamePhone()
        . $this->serializeShippingAddress()
        . $this->serializeIsCorrectError()
        . $this->serializeSecureVerificationData();
    }

    /**
     * Build the ExpirationDate, CardSecurityCode and Amount nodes
     *
     * @return string
     */
    protected function serializeCardInfo()
    {
        return sprintf(
            '<ExpirationDate>%s</ExpirationDate>%s<Amount currencyCode="%s">%.2f</Amount>',
            $this->getExpirationDate(),
            $this->serializeCardSecurityCode(),
            $this->getCurrencyCode(),
            $this->getAmount()
        );
    }

    /**
     * Build the CardSecurityCode or, if the payload is using encrypted data,
     * the EncryptedCardSecurityCode.
     *
     * @return string
     */
    protected function serializeCardSecurityCode()
    {
        return sprintf(
            '<%1$s>%2$s</%1$s>',
            $this->getIsEncrypted() ? self::ENCRYPTED_CVV_NODE : self::RAW_CVV_NODE,
            $this->getCardSecurityCode()
        );
    }
    /**
     * Build the BillingFirstName, BillingLastName and BillingPhoneNo nodes
     *
     * @return string
     */
    protected function serializeBillingNamePhone()
    {
        $template = '<BillingFirstName>%s</BillingFirstName>'
            . '<BillingLastName>%s</BillingLastName>'
            . '<BillingPhoneNo>%s</BillingPhoneNo>';
        return sprintf(
            $template,
            $this->getBillingFirstName(),
            $this->getBillingLastName(),
            $this->getBillingPhone()
        );
    }

    /**
     * Aggregate the billing address lines into the BillingAddress node
     *
     * @return string
     */
    protected function serializeBillingAddress()
    {
        $lines = [];
        $billingLines = is_array($this->billingLines) ? $this->billingLines : [];
        $idx = 0;
        foreach ($billingLines as $line) {
            $idx++;
            $lines[] = sprintf(
                '<Line%d>%s</Line%1$d>',
                $idx,
                $line
            );
        }

        return sprintf(
            '<BillingAddress>%s<City>%s</City>%s<CountryCode>%s</CountryCode>%s</BillingAddress>',
            implode('', $lines),
            $this->getBillingCity(),
            $this->nodeNullCoalesce('MainDivision', $this->getBillingMainDivision()),
            $this->getBillingCountryCode(),
            $this->nodeNullCoalesce('PostalCode', $this->getBillingPostalCode())
        );
    }

    /**
     * Build the CustomerEmail and CustomerIPAddress nodes
     *
     * @return string
     */
    protected function serializeCustomerInfo()
    {
        return sprintf(
            '<CustomerEmail>%s</CustomerEmail><CustomerIPAddress>%s</CustomerIPAddress>',
            $this->getEmail(),
            $this->getIp()
        );
    }

    /**
     * Build the ShippingFirstName, ShippingLastName and ShippinggPhoneNo nodes
     *
     * @return string
     */
    protected function serializeShippingNamePhone()
    {
        return sprintf(
            '<ShipToFirstName>%s</ShipToFirstName><ShipToLastName>%s</ShipToLastName><ShipToPhoneNo>%s</ShipToPhoneNo>',
            $this->getShipToFirstName(),
            $this->getShipToLastName(),
            $this->getShipToPhone()
        );
    }

    /**
     * Aggregate the shipping address lines into the ShippingAddress node
     *
     * @return string
     */
    protected function serializeShippingAddress()
    {
        $lines = [];
        $shippingLines = is_array($this->shipToLines) ? $this->shipToLines : [];
        $idx = 0;
        foreach ($shippingLines as $line) {
            $idx++;
            $lines[] = sprintf(
                '<Line%d>%s</Line%1$d>',
                $idx,
                $line
            );
        }

        return sprintf(
            '<ShippingAddress>%s<City>%s</City>%s<CountryCode>%s</CountryCode>%s</ShippingAddress>',
            implode('', $lines),
            $this->getShipToCity(),
            $this->nodeNullCoalesce('MainDivision', $this->getShipToMainDivision()),
            $this->getShipToCountryCode(),
            $this->nodeNullCoalesce('PostalCode', $this->getShipToPostalCode())
        );
    }

    /**
     * Build the isRequestToCorrectCVVOrAVSError node
     *
     * @return string
     */
    protected function serializeIsCorrectError()
    {
        $string = sprintf(
            '<isRequestToCorrectCVVOrAVSError>%s</isRequestToCorrectCVVOrAVSError>',
            $this->getIsRequestToCorrectCvvOrAvsError() ? 'true' : 'false'
        );
        return $string;
    }

    /**
     * Build the SecureVerificationData node
     *
     * @return string
     */
    protected function serializeSecureVerificationData()
    {
        // make sure we have all of the required fields for this node
        // if we don't then don't serialize it at all
        if (
            $this->getAuthenticationAvailable() &&
            $this->getAuthenticationStatus() &&
            $this->getCavvUcaf() &&
            $this->getTransactionId() &&
            $this->getPayerAuthenticationResponse()
        ) {
            $template = '<SecureVerificationData>'
                . '<AuthenticationAvailable>%s</AuthenticationAvailable>'
                . '<AuthenticationStatus>%s</AuthenticationStatus>'
                . '<CavvUcaf>%s</CavvUcaf>'
                . '<TransactionId>%s</TransactionId>'
                . '%s'
                . '<PayerAuthenticationResponse>%s</PayerAuthenticationResponse>'
                . '</SecureVerificationData>';
            return sprintf(
                $template,
                $this->getAuthenticationAvailable(),
                $this->getAuthenticationStatus(),
                $this->getCavvUcaf(),
                $this->getTransactionId(),
                $this->nodeNullCoalesce('ECI', $this->getEci()),
                $this->getPayerAuthenticationResponse()
            );
        } else {
            return '';
        }
    }

    /**
     * Trim any white space and return the resulting string truncating to $maxLength.
     *
     * Return null if the result is an empty string or not a string
     *
     * @param string $string
     * @param int $maxLength
     * @return string or null
     */
    protected function cleanString($string, $maxLength)
    {
        $value = null;

        if (is_string($string)) {
            $trimmed = substr(trim($string), 0, $maxLength);
            $value = empty($trimmed) ? null : $trimmed;
        }

        return $value;
    }

    /**
     * Make sure we have max 4 address lines of 70 chars max
     *
     * If there are more than 4 lines concatenate all extra lines with the 4th line.
     *
     * Truncate any lines to 70 chars max.
     *
     * @param string $lines
     * @return array or null
     */
    protected function cleanAddressLines($lines)
    {
        $finalLines = null;

        if (is_string($lines)) {
            $trimmed = trim($lines);
            $addressLines = explode("\n", $trimmed);

            $newLines = [];
            foreach ($addressLines as $line) {
                $newLines[] = $this->cleanString($line, 70);
            }

            if (count($newLines) > 4) {
                // concat lines beyond the four allowed down into the last line
                $newLines[3] = $this->cleanString(implode(' ', array_slice($newLines, 3)), 70);
            }

            $finalLines = array_slice($newLines, 0, 4);
        }

        return $finalLines;
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
     * There can be many address lines although only one is required
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

    /**
     * Take and XML string and configure this payload object
     *
     * @param string $string
     * @return $this|\eBayEnterprise\RetailOrderManagement\Payload\IPayload
     */
    public function deserialize($string)
    {
        $this->schemaValidate($string);
        $dom = new \DOMDocument();
        $dom->loadXML($string);

        $domXPath = new \DOMXPath($dom);
        $domXPath->registerNamespace('x', self::XML_NS);

        foreach ($this->extractionPaths as $property => $xPath) {
            $this->$property = $domXPath->evaluate($xPath);
        }

        foreach ($this->optionalExtractionPaths as $property => $xPath) {
            $node = $domXPath->query($xPath)->item(0);
            if ($node) {
                $this->$property = $node->nodeValue;
            }
        }

        // address lines and boolean values have to be handled specially
        $this->addressLinesFromXPath($domXPath);
        foreach ($this->booleanExtractionPaths as $property => $xPath) {
            $value = $domXPath->evaluate($xPath);
            $this->$property = $this->convertStringToBoolean($value);
        }

        // validate self, throws Exception\InvalidPayload if we don't pass
        $this->validate();

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
