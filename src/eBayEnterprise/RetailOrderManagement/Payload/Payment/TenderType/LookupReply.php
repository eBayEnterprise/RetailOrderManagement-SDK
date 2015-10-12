<?php

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment\TenderType;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload;
use Psr\Log\LoggerInterface;

class LookupReply implements ILookupReply
{
    use TTopLevelPayload {
        TTopLevelPayload::validate as protected baseValidate;
    }

    const ROOT_NODE = 'TenderTypeLookupReply';

    /** @var string */
    protected $tenderType;
    /** @var string */
    protected $responseCode;
    /** @var array */
    protected $responseCodeMessage;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        list(
            $this->validators,
            $this->schemaValidator,
            $this->payloadMap,
            $this->logger,
            $this->parentPayload
        ) = func_get_args();

        $this->extractionPaths = [
            'tenderType' => 'string(x:TenderType)',
            'responseCode' => 'string(x:ResponseCode)',
        ];
        $this->responseCodeMessage = [
            static::TENDER_TYPE_FOUND => 'A matching tender type is found by account number,'
                . ' tender class, currency code and store id.',
            static::PAN_FAILS_LUHN_CHECK => 'The account number fails to pass Luhn check.'
                . ' http://en.wikipedia.org/wiki/Luhn_algorithm',
            static::NO_TENDER_TYPE_FOUND => 'No tender type matches the account number, tender class,'
                . ' currency code and store id',
            static::PAN_NOT_CONFIGURED_TO_STORE => 'Tender type matches the account number, tender class'
                . ' and currency code but not the store id',
            static::UNKNOWN_FAILURE => 'Failed to find a tender type for unknown cause/s',
        ];
    }

    /**
     * Tender type
     *
     * @return string
     */
    public function getTenderType()
    {
        return $this->tenderType;
    }

    /**
     * @param string
     * @return self
     */
    public function setTenderType($tenderType)
    {
        $this->tenderType = $this->cleanString($tenderType, 4);
        return $this;
    }

    /**
     * code indicating the result of the request
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
    public function setResponseCode($responseCode)
    {
        $this->responseCode = trim($responseCode);
        return $this;
    }

    /**
     * @return string
     */
    public function getResponseMessage()
    {
        return isset($this->responseCodeMessage[$this->getResponseCode()])
            ? $this->responseCodeMessage[$this->getResponseCode()]
            : '';
    }

    /**
     * @return bool
     */
    public function isSuccessful()
    {
        return $this->getResponseCode() === static::TENDER_TYPE_FOUND;
    }

    public function validate()
    {
        $this->baseValidate();
        $this->validateResponseCode();
    }

    protected function validateResponseCode()
    {
        if (!isset($this->responseCodeMessage[$this->getResponseCode()])) {
            throw new InvalidPayload("Unrecognized response code '{$this->getResponseCode()}'");
        }
    }

    protected function serializeContents()
    {
        return $this->serializeRequiredValue('TenderType', $this->xmlEncode($this->getTenderType()))
            . $this->serializeRequiredValue('ResponseCode', $this->xmlEncode($this->getResponseCode()));
    }
    protected function deseraializeExtra()
    {
        $this->setTenderType($this->tenderType);
        $this->setResponseCode($this->responseCode);
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . static::XSD;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }
}
