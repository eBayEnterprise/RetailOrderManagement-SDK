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
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderDetailResponse implements IOrderDetailResponse
{
    use TTopLevelPayload;

    /** @var string */
    protected $orderType;
    /** @var string */
    protected $testType;
    /** @var string */
    protected $cancellable;
    /** @var IOrderResponse */
    protected $order;

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
            'orderType' => 'string(@orderType)',
            'cancellable' => 'string(@cancellable)',
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
            'testType' => '@testType',
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
            'order' => 'x:Order',
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
        $this->setOrder($this->buildPayloadForInterface(
            static::ORDER_RESPONSE_INTERFACE
        ));
        return $this;
    }

    /**
     * @see IOrderDetailResponse::getOrderType()
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @see IOrderDetailResponse::setOrderType()
     * @codeCoverageIgnore
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * @see IOrderDetailResponse::getTestType()
     */
    public function getTestType()
    {
        return $this->testType;
    }

   /**
     * @see IOrderDetailResponse::setTestType()
     * @codeCoverageIgnore
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;
        return $this;
    }

    /**
     * @see IOrderDetailResponse::getCancellable()
     */
    public function getCancellable()
    {
        return $this->cancellable;
    }

    /**
     * @see IOrderDetailResponse::setCancellable()
     * @codeCoverageIgnore
     */
    public function setCancellable($cancellable)
    {
        $this->cancellable = $cancellable;
        return $this;
    }

    /**
     * @see IOrderDetailResponse::getOrder()
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @see IOrderDetailResponse::setOrder()
     * @codeCoverageIgnore
     */
    public function setOrder(IOrderResponse $order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->getOrder()->serialize();
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

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    /**
     * Validate the serialized data via the schema validator.
     * @param  string
     * @return self
     */
    protected function schemaValidate($serializedData)
    {
        $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        return $this;
    }

    /**
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $testType = $this->getTestType();
        return array_merge(
            ['xmlns' => $this->getXmlNamespace(), 'orderType' => $this->getOrderType()],
            $testType ? ['testType' => $testType] : [],
            ['cancellable' => $this->getCancellable()]
        );
    }
}
