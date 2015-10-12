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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Order\TPaymentContainer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderDetailPayment implements IOrderDetailPayment
{
    use TPayload, TPaymentContainer;

    /** @var string */
    protected $billingAddress;
    /** @var string */
    protected $ref;
    /** @var string */
    protected $status;

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
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = $this->getNewPayloadFactory();

        $this->isSerializeEmptyNode = false;

        $this->initExtractPaths()
            ->initOptionalExtractPaths()
            ->initSubPayloadExtractPaths()
            ->initSubPayloadProperties();
    }

    /**
     * Initialize the protected class property array self::extractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initExtractPaths()
    {
        $this->extractionPaths = [
            'billingAddress' => 'string(x:BillingAddress)',
            'ref' => 'string(x:BillingAddress/@ref)',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::optionalExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initOptionalExtractPaths()
    {
        $this->optionalExtractionPaths = [
            'status' => 'x:Status',
        ];
        return $this;
    }

    /**
     * Initialize the protected class property array self::subpayloadExtractionPaths with xpath
     * key/value pairs.
     *
     * @return self
     */
    protected function initSubPayloadExtractPaths()
    {
        $this->subpayloadExtractionPaths = [
            'payments' => 'x:Payments',
        ];
        return $this;
    }

    /**
     * Initialize any sub-payload class properties with their concrete instance.
     *
     * @return self
     */
    protected function initSubPayloadProperties()
    {
        $this->setPayments($this->buildPayloadForInterface(
            static::PAYMENT_ITERABLE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IOrderDetailPayment::getBillingAddress()
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @see IOrderDetailPayment::setBillingAddress()
     * @codeCoverageIgnore
     */
    public function setBillingAddress($billingAddress)
    {
        $this->billingAddress = $billingAddress;
        return $this;
    }

    /**
     * @see IOrderDetailPayment::getRef()
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * @see IOrderDetailPayment::setRef()
     * @codeCoverageIgnore
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
        return $this;
    }
    /**
     * @see IOrderDetailPayment::getStatus()
     */
    public function getStatus()
    {
        return $this->status;
    }
    /**
     * @see IOrderDetailPayment::setStatus()
     * @codeCoverageIgnore
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeBillingAddressValue('BillingAddress')
            . $this->getPayments()->serialize()
            . $this->serializeOptionalXmlEncodedValue('Status', $this->getStatus());
    }

    /**
     * @see TPayload::getRootNodeName()
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see TPayload::getXmlNamespace()
     */
    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    /**
     * Serializing the billing address XML node.
     *
     * @param  string
     * @param  string
     * @return string | null
     */
    protected function serializeBillingAddressValue($nodeName)
    {
        $ref = $this->getRef();
        $refAttribute = $this->serializeOptionalAttribute('ref', $this->xmlEncode($ref));
        return $ref ? sprintf('<%s %s/>', $nodeName, $refAttribute) : null;
    }
}
