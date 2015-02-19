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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use DateTime;
use DOMDocument;
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderRejected implements IOrderRejected
{
    /** @var ISchemaValidator */
    protected $schemaValidator;
    /** @var IValidatorIterator */
    protected $validators;
    /** @var IPayload */
    protected $parentPayload;
    /** @var string $customerOrderId */
    protected $customerOrderId;
    /** @var string $storeId */
    protected $storeId;
    /** @var DateTime $orderCreateTimestamp */
    protected $orderCreateTimestamp;
    /** @var string $reason */
    protected $reason;
    /** @var string $code */
    protected $code;
    /** @var array XPath expressions to extract required data from the serialized payload (XML) */
    protected $extractionPaths = [
        'customerOrderId' => 'string(//x:OrderRejected/@customerOrderId)',
        'storeId' => 'string(//x:OrderRejected/@storeId)',
        'orderCreateTimestamp' => 'string(//x:OrderRejected/@orderCreateTimestamp)',
        'reason' => 'string(//x:OrderRejected/x:Reason)',
        'code' => 'string(//x:OrderRejected/x:Reason/@code)',
    ];

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator unused, kept to allow IPayloadMap to be passed
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
    }

    public function getEventType()
    {
        return self::ROOT_NODE;
    }

    public function serialize()
    {
        $this->validate();
        $xml = sprintf(
            self::REJECTED_XML_TEMPLATE,
            self::ROOT_NODE,
            self::XML_NS,
            $this->getCustomerOrderId(),
            $this->getStoreId(),
            $this->getOrderCreateTimestamp()->format('c'),
            $this->serializeReason()
        );
        $this->schemaValidate($xml);
        return $xml;
    }

    public function validate()
    {
        foreach ($this->validators as $validator) {
            $validator->validate($this);
        }
        return $this;
    }

    public function getCustomerOrderId()
    {
        return $this->customerOrderId;
    }

    public function setCustomerOrderId($customerOrderId)
    {
        $this->customerOrderId = $customerOrderId;
        return $this;
    }

    public function getStoreId()
    {
        return $this->storeId;
    }

    public function setStoreId($storeId)
    {
        $this->storeId = $storeId;
        return $this;
    }

    public function getOrderCreateTimestamp()
    {
        return $this->orderCreateTimestamp;
    }

    public function setOrderCreateTimestamp(DateTime $timestamp)
    {
        $this->orderCreateTimestamp = $timestamp;
        return $this;
    }

    /**
     * Create an XML string representing the Reason nodes
     * @return string|null
     */
    protected function serializeReason()
    {
        $reason = trim($this->getReason());
        $code = trim($this->getCode());
        return ($reason !== '' && $code !== '')
            ? sprintf(self::REASON_XML_TEMPLATE, self::REASON_XML_ROOT, $code, $reason)
            : null;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Validate the serialized data via the schema validator.
     * @param  string $serializedData
     * @return self
     */
    protected function schemaValidate($serializedData)
    {
        $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        return $this;
    }

    protected function getSchemaFile()
    {
        return __DIR__ . '/../../Schema/events/1.0/events/' . self::XSD;
    }

    public function deserialize($string)
    {
        $this->schemaValidate($string);
        $xpath = $this->getPayloadAsXPath($string);
        foreach ($this->extractionPaths as $property => $path) {
            $value = $xpath->evaluate($path);
            $this->$property = ($property === self::PROPERTY_ORDER_CREATE_TIMESTAMP) ? new DateTime($value) : $value;
        }
        return $this;
    }

    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new DOMXPath($this->getPayloadAsDoc($xmlString));
        $xpath->registerNamespace('x', self::XML_NS);
        return $xpath;
    }

    /**
     * Load the payload XML into a DOMDocument
     * @param  string $xmlString
     * @return \DOMDocument
     */
    protected function getPayloadAsDoc($xmlString)
    {
        $d = new DOMDocument();
        $d->loadXML($xmlString);
        return $d;
    }

    public function getParentPayload()
    {
        return $this->parentPayload;
    }
}
