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

namespace eBayEnterprise\RetailOrderManagement\Payload\Customer;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use DateTime;

class OrderSummary implements IOrderSummary
{
    use TPayload;

    /** @var string */
    protected $id;
    /** @var string */
    protected $orderType;
    /** @var string */
    protected $orderPurpose;
    /** @var string */
    protected $testType;
    /** @var DateTime */
    protected $modifiedTime;
    /** @var string */
    protected $cancellable;
    /** @var string */
    protected $customerOrderId;
    /** @var string */
    protected $customerId;
    /** @var DateTime */
    protected $orderDate;
    /** @var string */
    protected $dashboardRepId;
    /** @var string */
    protected $status;
    /** @var string */
    protected $orderTotal;
    /** @var string */
    protected $source;
    /** @var string */
    protected $chainedOrder;
    /** @var string */
    protected $type;
    /** @var string */
    protected $parentRef;
    /** @var string */
    protected $derivedOrder;

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
            'id' => 'string(@id)',
            'orderTotal' => 'string(x:OrderTotal)',
        ];
        $this->optionalExtractionPaths = [
            'orderType' => '@orderType',
            'orderPurpose' => '@orderPurpose',
            'testType' => '@testType',
            'cancellable' => '@cancellable',
            'customerOrderId' => 'x:CustomerOrderId',
            'customerId' => 'x:CustomerId',
            'dashboardRepId' => 'x:DashboardRepId',
            'status' => 'x:Status',
            'source' => 'x:Source',
            'chainedOrder' => 'x:ChainedOrder',
            'type' => 'x:ChainedOrder/@type',
            'parentRef' => 'x:ChainedOrder/@parentRef|x:DerivedOrder/@parentRef',
            'derivedOrder' => 'x:DerivedOrder',
        ];
        $this->datetimeExtractionPaths = [
            'modifiedTime' => 'string(@modifiedTime)',
            'orderDate' => 'string(x:OrderDate)',
        ];
    }

    /**
     * @see IOrderSummary::getId()
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @see IOrderSummary::setId()
     * @codeCoverageIgnore
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @see IOrderSummary::getOrderType()
     */
    public function getOrderType()
    {
        return $this->orderType;
    }

    /**
     * @see IOrderSummary::setOrderType()
     * @codeCoverageIgnore
     */
    public function setOrderType($orderType)
    {
        $this->orderType = $orderType;
        return $this;
    }

    /**
     * @see IOrderSummary::getOrderPurpose()
     */
    public function getOrderPurpose()
    {
        return $this->orderPurpose;
    }

    /**
     * @see IOrderSummary::setOrderPurpose()
     * @codeCoverageIgnore
     */
    public function setOrderPurpose($orderPurpose)
    {
        $this->orderPurpose = $orderPurpose;
        return $this;
    }

   /**
     * @see IOrderSummary::getTestType()
     */
    public function getTestType()
    {
        return $this->testType;
    }

   /**
     * @see IOrderSummary::setTestType()
     * @codeCoverageIgnore
     */
    public function setTestType($testType)
    {
        $this->testType = $testType;
        return $this;
    }

   /**
     * @see IOrderSummary::getModifiedTime()
     */
    public function getModifiedTime()
    {
        return $this->modifiedTime;
    }

   /**
     * @see IOrderSummary::setModifiedTime()
     * @codeCoverageIgnore
     */
    public function setModifiedTime(DateTime $modifiedTime)
    {
        $this->modifiedTime = $modifiedTime;
        return $this;
    }

    /**
     * @see IOrderSummary::getCancellable()
     */
    public function getCancellable()
    {
        return $this->cancellable;
    }

    /**
     * @see IOrderSummary::setCancellable()
     * @codeCoverageIgnore
     */
    public function setCancellable($cancellable)
    {
        $this->cancellable = $cancellable;
        return $this;
    }

    /**
     * @see IOrderSummary::getCustomerId()
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

     /**
     * @see IOrderSummary::setCustomerId()
     * @codeCoverageIgnore
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

   /**
     * @see IOrderSummary::getCustomerOrderId()
     */
    public function getCustomerOrderId()
    {
        return $this->customerOrderId;
    }

    /**
     * @see IOrderSummary::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setCustomerOrderId($customerOrderId)
    {
        $this->customerOrderId = $customerOrderId;
        return $this;
    }

    /**
     * @see IOrderSummary::getOrderDate()
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * @see IOrderSummary::setOrderDate()
     * @codeCoverageIgnore
     */
    public function setOrderDate(DateTime $orderDate)
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    /**
     * @see IOrderSummary::getDashboardRepId()
     */
    public function getDashboardRepId()
    {
        return $this->dashboardRepId;
    }

    /**
     * @see IOrderSummary::setDashboardRepId()
     * @codeCoverageIgnore
     */
    public function setDashboardRepId($dashboardRepId)
    {
        $this->dashboardRepId = $dashboardRepId;
        return $this;
    }

    /**
     * @see IOrderSummary::getStatus()
     */
    public function getStatus()
    {
        return $this->status;
    }

   /**
     * @see IOrderSummary::setStatus()
     * @codeCoverageIgnore
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @see IOrderSummary::getOrderTotal()
     */
    public function getOrderTotal()
    {
        return $this->orderTotal;
    }

   /**
     * @see IOrderSummary::setOrderTotal()
     * @codeCoverageIgnore
     */
    public function setOrderTotal($orderTotal)
    {
        $this->orderTotal = $orderTotal;
        return $this;
    }

    /**
     * @see IOrderSummary::getSource()
     */
    public function getSource()
    {
        return $this->source;
    }

   /**
     * @see IOrderSummary::setSource()
     * @codeCoverageIgnore
     */
    public function setSource($source)
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @see IOrderSummary::getChainedOrder()
     */
    public function getChainedOrder()
    {
        return $this->chainedOrder;
    }

    /**
     * @see IOrderSummary::setChainedOrder()
     * @codeCoverageIgnore
     */
    public function setChainedOrder($chainedOrder)
    {
        $this->chainedOrder = $chainedOrder;
        return $this;
    }

   /**
     * @see IOrderSummary::getType()
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @see IOrderSummary::setType()
     * @codeCoverageIgnore
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @see IOrderSummary::getParentRef()
     */
    public function getParentRef()
    {
        return $this->parentRef;
    }

    /**
     * @see IOrderSummary::setParentRef()
     * @codeCoverageIgnore
     */
    public function setParentRef($parentRef)
    {
        $this->parentRef = $parentRef;
        return $this;
    }

    /**
     * @see IOrderSummary::getDerivedOrder()
     */
    public function getDerivedOrder()
    {
        return $this->derivedOrder;
    }

    /**
     * @see IOrderSummary::setDerivedOrder()
     * @codeCoverageIgnore
     */
    public function setDerivedOrder($derivedOrder)
    {
        $this->derivedOrder = $derivedOrder;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeOptionalXmlEncodedValue('CustomerOrderId', $this->getCustomerOrderId())
            . $this->serializeOptionalXmlEncodedValue('CustomerId', $this->getCustomerId())
            . $this->serializeOptionalDateValue('OrderDate', 'c', $this->getOrderDate())
            . $this->serializeOptionalXmlEncodedValue('DashboardRepId', $this->getDashboardRepId())
            . $this->serializeOptionalXmlEncodedValue('Status', $this->getStatus())
            . $this->serializeRequiredValue('OrderTotal', $this->xmlEncode($this->getOrderTotal()))
            . $this->serializeOptionalXmlEncodedValue('Source', $this->getSource())
            . $this->serializeChainedOrder('ChainedOrder', $this->xmlEncode($this->getChainedOrder()))
            . $this->serializeDerivedOrder('DerivedOrder', $this->xmlEncode($this->getDerivedOrder()));
    }

    /**
     * Serialize the chain order node.
     * @return string | null
     */
    protected function serializeChainedOrder($node, $value)
    {
        $parentRef = $this->getParentRef();
        $typeAttribute = $this->serializeOptionalAttribute('type', $this->xmlEncode($this->getType()));
        $parentRefAttribute = $this->serializeOptionalAttribute('parentRef', $this->xmlEncode($parentRef));
        return $parentRef
            ? sprintf('<%s %s %s>%s</%1$s>', $node, $typeAttribute, $parentRefAttribute, $value) : null;
    }

    /**
     * Serialize the derived order node.
     * @return string | null
     */
    protected function serializeDerivedOrder($node, $value)
    {
        $parentRef = $this->getParentRef();
        $parentRefAttribute = $this->serializeOptionalAttribute('parentRef', $this->xmlEncode($parentRef));
        return $parentRef
            ? sprintf('<%s %s>%s</%1$s>', $node, $parentRefAttribute, $value) : null;
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
     * @see TPayload::getRootAttributes()
     */
    protected function getRootAttributes()
    {
        $orderType = $this->getOrderType();
        $orderPurpose = $this->getOrderPurpose();
        $testType = $this->getTestType();
        $cancellable = $this->getCancellable();
        return array_merge(
            ['id' => $this->getId()],
            $orderType ? ['orderType' => $orderType] : [],
            $orderPurpose ? ['orderPurpose' => $orderPurpose] : [],
            $testType ? ['testType' => $testType] : [],
            ['modifiedTime' => $this->getModifiedTime()->format('c')],
            $cancellable ? ['cancellable' => $cancellable] : []
        );
    }
}
