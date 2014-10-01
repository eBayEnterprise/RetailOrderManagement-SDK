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

class CreditCardAuthRequest implements ICreditCardAuthRequest
{
    /** @var string **/
    protected $requestId;
    /** @var string **/
    protected $orderId;
    /** @var bool **/
    protected $panIsToken;
    /** @var string **/
    protected $cardNumber;
    /** @var \DateTime **/
    protected $expirationDate;
    /** @var string **/
    protected $cardSecurityCode;
    /** @var float **/
    protected $amount;
    /** @var string **/
    protected $currencyCode;
    /** @var string **/
    protected $billingFirstName;
    /** @var string **/
    protected $billingLastName;
    /** @var string **/
    protected $billingPhone;
    /** @var array **/
    protected $billingLines;
    /** @var string **/
    protected $billingCity;
    /** @var string **/
    protected $billingMainDivision;
    /** @var string **/
    protected $billingCountryCode;
    /** @var string **/
    protected $billingPostalCode;
    /** @var string **/
    protected $customerEmail;
    /** @var string **/
    protected $customerIpAddress;
    /** @var string **/
    protected $shipToFirstName;
    /** @var string **/
    protected $shipToLastName;
    /** @var string **/
    protected $shipToPhone;
    /** @var array **/
    protected $shipToLines;
    /** @var string **/
    protected $shipToCity;
    /** @var string **/
    protected $shipToMainDivision;
    /** @var string **/
    protected $shipToCountryCode;
    /** @var string **/
    protected $shipToPostalCode;
    /** @var bool **/
    protected $isRequestToCorrectCVVOrAVSError;
    /** @var string **/
    protected $authenticationAvailable;
    /** @var string **/
    protected $authenticationStatus;
    /** @var string **/
    protected $cavvUcaf;
    /** @var string **/
    protected $transactionId;
    /** @var string **/
    protected $eci;
    /** @var string **/
    protected $payerAuthenticationResponse;
    /** @var IValidatorIterator */
    protected $validators;

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

        if (is_a($string, 'string')) {
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
     * @return string or null
     */
    protected function cleanAddressLines($lines)
    {
        $finalLines = null;

        if (is_a($lines, 'string')) {
            $trimmed = trim($lines);
            $addressLines = explode('\n', $trimmed);

            $newLines = array();
            foreach ($addressLines as $line) {
                $newLines[] = substr(trim($line), 0, 70);
            }

            if (count($newLines) > 4) {
                $extraLines = array_slice($newLines, 3);
                $lastLine = implode('', $extraLines);
                $lastLine = substr(trim($lastLine), 0, 70);
                $newLines[3] = $lastLine;
            }

            $finalLines = array_slice($newLines, 0, 4);
        }

        return $finalLines;
    }

    public function __construct(IValidatorIterator $validators)
    {
        $this->validators = $validators;
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

    public function getOrderId()
    {
        return $this->orderId;
    }

    public function setOrderId($orderId)
    {
        $this->orderId = $this->cleanString($orderId, 20);
        return $this;
    }

    public function getPanIsToken()
    {
        return $this->panIsToken;
    }

    public function setPanIsToken($isToken)
    {
        $this->panIsToken = is_a($isToken, 'bool') ? $isToken : null;
        return $this;
    }

    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    public function setCardNumber($ccNum)
    {
        $this->cardNumber = $this->cleanString($ccNum, 22);
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
        $value = null;

        $cleaned = $this->cleanString($cvv, 4);
        if ($cleaned !== null) {
            if (strlen($cleaned) < 3) {
                $value = $cleaned;
            }
        }
        $this->cardSecurityCode = $value;

        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        if (is_a($amount, 'float')) {
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
            if (strlen($cleaned) < 3) {
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
            $match = preg_match(
                '([a-zA-Z0-9_\-])([a-zA-Z0-9_\-\.]*)@(\[((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}|((([a-zA-Z0-9\-]+)\.)+))([a-zA-Z]{2,}|(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\])',
                $cleaned
            );

            if ($match === 1) {
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
        $value = null;

        $cleaned = $this->cleanString($ip, 70);
        if ($cleaned !== null) {
            $match = preg_match(
                '((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])',
                $cleaned
            );

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

        if (is_a($name, 'string')) {
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

        if (is_a($name, 'string')) {
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

        if (is_a($phone, 'string')) {
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
        return implode('\n', $this->billingLines);
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
        $this->requestId = $this->cleanString($city, 35);
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
        if (strlen($cleaned) < 2) {
            $this->billingCountryCode = null;
        } else {
            $this->billingCountryCode = $cleaned;
        }

        return $this;
    }

    public function getBillingPostalCode()
    {
        return $this->billingPostalCode;
    }

    public function setBillingPostalCode($code)
    {
        $this->requestId = $this->cleanString($code, 15);
        return $this;
    }

    public function getShipToFirstName()
    {
        return $this->shipToFirstName;
    }

    public function setShipToFirstName($name)
    {
        $value = null;

        if (is_a($name, 'string')) {
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

        if (is_a($name, 'string')) {
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

        if (is_a($phone, 'string')) {
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
        return implode('\n', $this->shipToLines);
    }

    public function setShipToLines($lines)
    {
        $this->requestId = $this->cleanAddressLines($lines);
        return $this;
    }

    public function getShipToCity()
    {
        return $this->requestId;
    }

    public function setShipToCity($city)
    {
        $cleaned = $this->cleanString($city, 40);
        if (strlen($cleaned) < 2) {
            $this->shipToCity = null;
        } else {
            $this->shipToCity = $cleaned;
        }

        return $this;
    }

    public function getShipToMainDivision()
    {
        return $this->shipToCity;
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
        if (strlen($cleaned) < 2) {
            $this->shipToCountryCode = null;
        } else {
            $this->shipToCountryCode = $cleaned;
        }

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
        return $this->$isRequestToCorrectCVVOrAVSError;
    }

    public function setIsRequestToCorrectCvvOrAvsError($flag)
    {
        if (is_a($flag, 'bool')) {
            $this->isRequestToCorrectCVVOrAVSError = $flag;
        } else {
            $this->isRequestToCorrectCVVOrAVSError = null;
        }

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

        if (is_a($eci, 'string')) {
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

    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }

        return $this;
    }

    public function serialize()
    {
        $writer = new XMLWriter();
        $writer->openMemory();
        $writer->startDocument('1.0', 'URF-8');

        $writer->startElement('CreditCardAuthRequest');
        if (!is_null($this->requestId)) {
            $writer->writeAttribute('requestId', $this->requestId);
        }
        $writer->writeAttribute('xmlns', 'http://api.gsicommerce.com/schema/checkout/1.0');

        if (!is_null($this->orderId) && !is_null($this->cardNumber)) {
            $writer->startElement('PaymentContext');
            $writer->writeElement('orderId', $this->orderId);
            $writer->startElement('PaymentAccountUniqueId', $this->cardNumber);
            if (!is_null($this->panIsToken)) {
                $writer->writeAttribute('isToken', $this->panIsToken);
            }
            $writer->endElement('PaymentAccountUniqueId');
            $writer->endElement('PaymentContext');
        }

        if (!is_null($this->expirationDate)) {
            $writer->writeElement('ExpirationDate', $this->expirationDate);
        }

        if (!is_null($this->cardSecurityCode)) {
            $writer->writeElement('CardSecurityCode', $this->cardSecurityCode);
        }

        if (!is_null($this->amount)) {
            $writer->writeElement('Amount', $this->expirationDate);
        }

        if (!is_null($this->billingFirstName)) {
            $writer->writeElement('BillingFirstName', $this->billingFirstName);
        }

        if (!is_null($this->billingLastName)) {
            $writer->writeElement('BillingLasttName', $this->billingLastName);
        }

        if (!is_null($this->billingPhone)) {
            $writer->writeElement('BillingPhoneNo', $this->billingPhone);
        }

        $writer->startElement('BillingAddress');
        if (!is_null($this->billingLines)) {
            foreach ($this->billingLines as $line) {
                $writer->writeElement('Line'.$this->billingLines->index(), $this->billingLines);
            }
        }

        if (!is_null($this->billingCity)) {
            $writer->writeElement('City', $this->billingCity);
        }

        if (!is_null($this->billingMainDivision)) {
            $writer->writeElement('MainDivision', $this->billingMainDivision);
        }

        if (!is_null($this->billingCountryCode)) {
            $writer->writeElement('CountryCode', $this->billingCountryCode);
        }

        if (!is_null($this->billingPostalCode)) {
            $writer->writeElement('PostalCode', $this->billingPostalCode);
        }
        $writer->endelement('BillingAddress');

        if (!is_null($this->customerEmail)) {
            $writer->writeElement('CustomerEmail', $this->customerEmail);
        }

        if (!is_null($this->customerIpAddress)) {
            $writer->writeElement('CustomerIPAddress', $this->customerIpAddress);
        }

        if (!is_null($this->billingFirstName)) {
            $writer->writeElement('BillingFirstName', $this->billingFirstName);
        }

        if (!is_null($this->billingLastName)) {
            $writer->writeElement('BillingLasttName', $this->billingLastName);
        }

        if (!is_null($this->billingPhone)) {
            $writer->writeElement('BillingPhoneNo', $this->billingPhone);
        }

        $writer->startElement('ShippingAddress');
        if (!is_null($this->shipToLines)) {
            foreach ($this->shipToLines as $line) {
                $writer->writeElement('Line'.$this->shipToLines->index(), $this->shipToLines);
            }
        }

        if (!is_null($this->shipToCity)) {
            $writer->writeElement('City', $this->shipToCity);
        }

        if (!is_null($this->shipToMainDivision)) {
            $writer->writeElement('MainDivision', $this->shipToMainDivision);
        }

        if (!is_null($this->shipToCountryCode)) {
            $writer->writeElement('CountryCode', $this->shipToCountryCode);
        }

        if (!is_null($this->shipToPostalCode)) {
            $writer->writeElement('PostalCode', $this->shipToPostalCode);
        }
        $writer->endelement('ShippingAddress');

        if (!is_null($this->isRequestToCorrectCVVOrAVSError)) {
            $writer->writeElement('isRequestToCorrectCVVOrAVSError', $this->isRequestToCorrectCVVOrAVSError);
        }

        $writer->startElement('SecureVerificationData');
        if (!is_null($this->authenticationAvailable)) {
            $writer->writeElement('AuthenticationAvailable', $this->authenticationAvailable);
        }

        if (!is_null($this->authenticationStatus)) {
            $writer->writeElement('AuthenticationStatus', $this->authenticationStatus);
        }

        if (!is_null($this->cavvUcaf)) {
            $writer->writeElement('CavvUcaf', $this->cavvUcaf);
        }

        if (!is_null($this->transactionId)) {
            $writer->writeElement('TransactionId', $this->transactionId);
        }

        if (!is_null($this->eci)) {
            $writer->writeElement('ECI', $this->eci);
        }

        if (!is_null($this->payerAuthenticationResponse)) {
            $writer->writeElement('PayerAuthenticationResponse', $this->payerAuthenticationResponse);
        }
        $writer->endElement('SecureVerificationData');
        $writer->startElement('CreditCardAuthRequest');
        $writer->endDocument();

        $xml = $writer->outputMemory();

        $doc = DOMDocument::loadXML($xml);

        return $doc->C14N();
    }

    public function deserialize($string)
    {

    }
}
