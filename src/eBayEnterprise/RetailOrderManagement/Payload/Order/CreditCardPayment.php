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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CreditCardPayment implements ICreditCardPayment
{
    use TPayload, TAmount, TCustomAttributeContainer, TCardPayment;

    const ROOT_NODE = 'CreditCard';

    /** @var DateTime */
    protected $createTimeStamp;
    /** @var float */
    protected $amount;
    /** @var string */
    protected $responseCode;
    /** @var string */
    protected $bankAuthorizationCode;
    /** @var string */
    protected $cvv2ResponseCode;
    /** @var string */
    protected $avsResponseCode;
    /** @var string */
    protected $phoneResponseCode;
    /** @var string */
    protected $nameResponseCode;
    /** @var string */
    protected $emailResponseCode;
    /** @var string */
    protected $extendedAuthDescription;
    /** @var string */
    protected $extendedAuthReasonCode;
    /** @var float */
    protected $amountAuthorized;
    /** @var DateTime */
    protected $expirationDate;
    /** @var DateTime */
    protected $startDate;
    /** @var string */
    protected $issueNumber;
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
    /** @var string */
    protected $purchasePlanCode;
    /** @var string */
    protected $purchasePlanDescription;
    /** @var array */
    protected $allowedResponseCodes = [
        self::RESPONSE_CODE_APPROVED,
        self::RESPONSE_CODE_ACCEPTORDERWITHEXCEPTION,
        self::RESPONSE_CODE_AVS,
        self::RESPONSE_CODE_AVSPIN,
        self::RESPONSE_CODE_DECLF,
        self::RESPONSE_CODE_DECLR,
        self::RESPONSE_CODE_DECL,
        self::RESPONSE_CODE_PAYMENTPROCESSORTIMEOUT,
        self::RESPONSE_CODE_PIN,
        self::RESPONSE_CODE_NOK,
        self::RESPONSE_CODE_OK,
    ];

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->extractionPaths = [
            'responseCode' => 'string(x:Authorization/x:ResponseCode)',
            'bankAuthorizationCode' => 'string(x:Authorization/x:BankAuthorizationCode)',
            'cvv2ResponseCode' => 'string(x:Authorization/x:CVV2ResponseCode)',
            'avsResponseCode' => 'string(x:Authorization/x:AVSResponseCode)',
            'orderId' => 'string(x:PaymentContext/x:PaymentSessionId)',
            'tenderType' => 'string(x:PaymentContext/x:TenderType)',
            'accountUniqueId' => 'string(x:PaymentContext/x:PaymentAccountUniqueId)',
            'paymentRequestId' => 'string(x:PaymentRequestId)',
        ];
        $this->optionalExtractionPaths = [
            'amount' => 'x:Amount',
            'phoneResponseCode' => 'x:Authorization/x:PhoneResponseCode',
            'nameResponseCode' => 'x:Authorization/x:NameResponseCode',
            'emailResponseCode' => 'x:Authorization/x:EmailResponseCode',
            'extendedAuthDescription' => 'x:Authorization/x:ExtendedAuthorizationResponseCodes/x:ResponseCodeDescription',
            'extendedAuthReasonCode' => 'x:Authorization/x:ExtendedAuthorizationResponseCodes/x:ReasonCode',
            'amountAuthorized' => 'x:Authorization/x:AmountAuthorized',
            'issueNumber' => 'x:IssueNumber',
            'authenticationAvailable' => 'x:SecureVerificationData/x:AuthenticationAvailable',
            'authenticationStatus' => 'x:SecureVerificationData/x:AuthenticationStatus',
            'cavvUcaf' => 'x:SecureVerificationData/x:CavvUcaf',
            'transactionId' => 'x:SecureVerificationData/x:TransactionId',
            'eci' => 'x:SecureVerificationData/x:ECI',
            'payerAuthenticationResponse' => 'x:SecureVerificationData/x:PayerAuthenticationResponse',
            'purchasePlanCode' => 'x:PurchasePlanCode',
            'purchasePlanDescription' => 'x:PurchasePlanDescription',
        ];
        $this->booleanExtractionPaths = [
            'isMockPayment' => 'string(@isMockPayment)',
            'panIsToken' => 'string(x:PaymentContext/x:PaymentAccountUniqueId/@isToken)'
        ];
        $this->subpayloadExtractionPaths = [
            'customAttributes' => 'x:CustomAttributes',
        ];

        $this->customAttributes = $this->buildPayloadForInterface(self::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE);
    }

    public function getCreateTimeStamp()
    {
        return $this->createTimeStamp;
    }

    public function setCreateTimeStamp(DateTime $createTimeStamp)
    {
        $this->createTimeStamp = $createTimeStamp;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    public function getResponseCode()
    {
        return $this->responseCode;
    }

    public function setResponseCode($responseCode)
    {
        $this->responseCode = in_array($responseCode, $this->allowedResponseCodes)
            ? $responseCode
            : null;
        return $this;
    }

    public function getBankAuthorizationCode()
    {
        return $this->bankAuthorizationCode;
    }

    public function setBankAuthorizationCode($bankAuthorizationCode)
    {
        $this->bankAuthorizationCode = $bankAuthorizationCode;
        return $this;
    }

    public function getCVV2ResponseCode()
    {
        return $this->cvv2ResponseCode;
    }

    public function setCVV2ResponseCode($cvv2ResponseCode)
    {
        $this->cvv2ResponseCode = $cvv2ResponseCode;
        return $this;
    }

    public function getAVSResponseCode()
    {
        return $this->avsResponseCode;
    }

    public function setAVSResponseCode($avsResponseCode)
    {
        $this->avsResponseCode = $avsResponseCode;
        return $this;
    }

    public function getPhoneResponseCode()
    {
        return $this->phoneResponseCode;
    }

    public function setPhoneResponseCode($phoneResponseCode)
    {
        $this->phoneResponseCode = $phoneResponseCode;
        return $this;
    }

    public function getNameResponseCode()
    {
        return $this->nameResponseCode;
    }

    public function setNameResponseCode($nameResponseCode)
    {
        $this->nameResponseCode = $nameResponseCode;
        return $this;
    }

    public function getEmailResponseCode()
    {
        return $this->emailResponseCode;
    }

    public function setEmailResponseCode($emailResponseCode)
    {
        $this->emailResponseCode = $emailResponseCode;
        return $this;
    }

    public function getExtendedAuthDescription()
    {
        return $this->extendedAuthDescription;
    }

    public function setExtendedAuthDescription($extendedAuthDescription)
    {
        $this->extendedAuthDescription = $extendedAuthDescription;
        return $this;
    }

    public function getExtendedAuthReasonCode()
    {
        return $this->extendedAuthReasonCode;
    }

    public function setExtendedAuthReasonCode($extendedAuthReasonCode)
    {
        $this->extendedAuthReasonCode = $extendedAuthReasonCode;
        return $this;
    }

    public function getAmountAuthorized()
    {
        return $this->amountAuthorized;
    }

    public function setAmountAuthorized($amountAuthorized)
    {
        $this->amountAuthorized = $this->sanitizeAmount($amountAuthorized);
        return $this;
    }

    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    public function setExpirationDate(DateTime $expirationDate)
    {
        $this->expirationDate = $expirationDate;
        return $this;
    }

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $startDate)
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getIssueNumber()
    {
        return $this->issueNumber;
    }

    public function setIssueNumber($issueNumber)
    {
        $this->issueNumber = $issueNumber;
        return $this;
    }

    public function getAuthenticationAvailable()
    {
        return $this->authenticationAvailable;
    }

    public function setAuthenticationAvailable($authenticationAvailable)
    {
        $this->authenticationAvailable = $authenticationAvailable;
        return $this;
    }

    public function getAuthenticationStatus()
    {
        return $this->authenticationStatus;
    }

    public function setAuthenticationStatus($authenticationStatus)
    {
        $this->authenticationStatus = $authenticationStatus;
        return $this;
    }

    public function getCavvUcaf()
    {
        return $this->cavvUcaf;
    }

    public function setCavvUcaf($cavvUcaf)
    {
        $this->cavvUcaf = $this->cleanString($cavvUcaf, 64);
        return $this;
    }

    public function getTransactionId()
    {
        return $this->transactionId;
    }

    public function setTransactionId($transactionId)
    {
        $this->transactionId = $this->cleanString($transactionId, 64);
        return $this;
    }

    public function getECI()
    {
        return $this->eci;
    }

    public function setECI($eci)
    {
        $this->eci = $eci;
        return $this;
    }

    public function getPayerAuthenticationResponse()
    {
        return $this->payerAuthenticationResponse;
    }

    public function setPayerAuthenticationResponse($payerAuthenticationResponse)
    {
        $this->payerAuthenticationResponse = $this->cleanString($payerAuthenticationResponse, 10000);
        return $this;
    }

    public function getPurchasePlanCode()
    {
        return $this->purchasePlanCode;
    }

    public function setPurchasePlanCode($purchasePlanCode)
    {
        $this->purchasePlanCode = $purchasePlanCode;
        return $this;
    }

    public function getPurchasePlanDescription()
    {
        return $this->purchasePlanDescription;
    }

    public function setPurchasePlanDescription($purchasePlanDescription)
    {
        $this->purchasePlanDescription = $purchasePlanDescription;
        return $this;
    }

    protected function serializeContents()
    {
        return $this->serializePaymentContext()
            . $this->serializePaymentRequestId()
            . "<CreateTimeStamp>{$this->getCreateTimeStamp()->format('c')}</CreateTimeStamp>"
            . $this->serializeOptionalAmount('Amount', $this->getAmount())
            . $this->serializeAuthorizations()
            . $this->serializeOptionalDateValue('ExpirationDate', 'Y-m', $this->getExpirationDate())
            . $this->serializeOptionalDateValue('StartDate', 'Y-m', $this->getStartDate())
            . $this->serializeOptionalValue('IssueNumber', $this->xmlEncode($this->getIssueNumber()))
            . $this->serializeSecureVerificationData()
            . $this->serializeOptionalValue('PurchasePlanCode', $this->xmlEncode($this->getPurchasePlanCode()))
            . $this->serializeOptionalValue('PurchasePlanDescription', $this->xmlEncode($this->getPurchasePlanDescription()))
            . $this->getCustomAttributes()->serialize();
    }

    /**
     * Serialize the credit card authorizations.
     *
     * @return string
     */
    protected function serializeAuthorizations()
    {
        return '<Authorization>'
            . "<ResponseCode>{$this->xmlEncode($this->getResponseCode())}</ResponseCode>"
            . "<BankAuthorizationCode>{$this->xmlEncode($this->getBankAuthorizationCode())}</BankAuthorizationCode>"
            . "<CVV2ResponseCode>{$this->xmlEncode($this->getCVV2ResponseCode())}</CVV2ResponseCode>"
            . "<AVSResponseCode>{$this->xmlEncode($this->getAVSResponseCode())}</AVSResponseCode>"
            . $this->serializeOptionalValue('PhoneResponseCode', $this->xmlEncode($this->getPhoneResponseCode()))
            . $this->serializeOptionalValue('NameResponseCode', $this->xmlEncode($this->getNameResponseCode()))
            . $this->serializeOptionalValue('EmailResponseCode', $this->xmlEncode($this->getEmailResponseCode()))
            . $this->serializeExtendedAuthorizations()
            . $this->serializeOptionalAmount('AmountAuthorized', $this->getAmountAuthorized())
            . '</Authorization>';
    }

    /**
     * Serialize the extended authorizations. Only include if both the extended
     * auth reason code and extended auth response code description are included.
     * Return an empty string, no serialization, if either is missing.
     *
     * @return string
     */
    protected function serializeExtendedAuthorizations()
    {
        $code = $this->getExtendedAuthReasonCode();
        $description = $this->getExtendedAuthDescription();
        return $code && $description
            ? '<ExtendedAuthorizationResponseCodes>'
                . "<ResponseCodeDescription>{$this->xmlEncode($description)}</ResponseCodeDescription>"
                . "<ReasonCode>{$this->xmlEncode($code)}</ReasonCode>"
                . '</ExtendedAuthorizationResponseCodes>'
            : '';
    }

    /**
     * Serialize sercure verification data (Verified by VISA). Will only be
     * included if a payer authentication response is included.
     *
     * @return string
     */
    protected function serializeSecureVerificationData()
    {
        $payerAuthResponse = $this->getPayerAuthenticationResponse();
        if ($payerAuthResponse) {
            return '<SecureVerificationData>'
                . $this->serializeOptionalValue('AuthenticationAvailable', $this->xmlEncode($this->getAuthenticationAvailable()))
                . $this->serializeOptionalValue('AuthenticationStatus', $this->xmlEncode($this->getAuthenticationStatus()))
                . $this->serializeOptionalValue('CavvUcaf', $this->xmlEncode($this->getCavvUcaf()))
                . $this->serializeOptionalValue('TransactionId', $this->xmlEncode($this->getTransactionId()))
                . $this->serializeOptionalValue('ECI', $this->xmlEncode($this->getECI()))
                . "<PayerAuthenticationResponse>{$this->xmlEncode($this->getPayerAuthenticationResponse())}</PayerAuthenticationResponse>"
                . '</SecureVerificationData>';
        }
        return '';
    }

    /**
     * Deserialize date time fields as DateTime objects instead of plain string
     * values.
     *
     * @return self
     */
    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $dateTimePaths = [
            'createTimeStamp' => 'string(x:CreateTimeStamp)',
            'expirationDate' => 'string(x:ExpirationDate)',
            'startDate' => 'string(x:StartDate)',
        ];
        foreach ($dateTimePaths as $prop => $extractionPath) {
            $value = $xpath->evaluate($extractionPath);
            $this->$prop = $value ? new DateTime($value) : null;
        }
        return $this;
    }

    protected function getRootAttributes()
    {
        $isMockPayment = $this->getIsMockPayment();
        return !is_null($isMockPayment)
            ? ['isMockPayment' => $this->convertBooleanToString($isMockPayment)]
            : [];
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
