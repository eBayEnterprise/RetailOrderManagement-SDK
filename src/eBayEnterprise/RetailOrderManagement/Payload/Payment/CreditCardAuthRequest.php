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
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CreditCardAuthRequest implements ICreditCardAuthRequest
{
    use TTopLevelPayload, TPaymentContext, TBillingAddress, TShippingAddress;

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

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->parentPayload = $parentPayload;

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

    public function getEmail()
    {
        return $this->customerEmail;
    }

    public function getIp()
    {
        return $this->customerIpAddress;
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

    public function getIsRequestToCorrectCvvOrAvsError()
    {
        return $this->isRequestToCorrectCVVOrAVSError;
    }

    public function setIsRequestToCorrectCvvOrAvsError($flag)
    {
        $this->isRequestToCorrectCVVOrAVSError = is_bool($flag) ? $flag : null;
        return $this;
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
        if ($this->getAuthenticationAvailable() &&
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
     * The XML namespace for the payload.
     *
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
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

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
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
