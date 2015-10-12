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

class OrderSearch implements IOrderSearch
{
    use TPayload;

    /** @var string */
    protected $customerId;
    /** @var string */
    protected $customerOrderId;

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
            'customerOrderId' => "string(x:CustomerOrderId)",
            'customerId' => 'string(x:CustomerId)',
        ];
    }

    /**
     * @see IOrderSearch::getCustomerId()
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @see IOrderSearch::setCustomerId()
     * @codeCoverageIgnore
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @see IOrderSearch::getCustomerOrderId()
     */
    public function getCustomerOrderId()
    {
        return $this->customerOrderId;
    }

    /**
     * @see IOrderSearch::setCustomerOrderId()
     * @codeCoverageIgnore
     */
    public function setCustomerOrderId($customerOrderId)
    {
        $this->customerOrderId = $customerOrderId;
        return $this;
    }

    /**
     * @see TPayload::serializeContents()
     */
    protected function serializeContents()
    {
        return $this->serializeChosenFilter();
    }

    /**
     * The node CustomerOrderId will be serialized only if the self::customerId protected property
     * is null and the self::customerOrderId protected property is a non-empty string. Otherwise,
     * only the CustomerId node will be serialized.
     * @return string
     */
    protected function serializeChosenFilter()
    {
        $customerId = $this->getCustomerId();
        $customerOrderId = $this->getCustomerOrderId();
        return !empty($customerId) || empty($customerOrderId)
            ? $this->serializeRequiredValue('CustomerId', $this->xmlEncode($customerId))
            : $this->serializeRequiredValue('CustomerOrderId', $this->xmlEncode($customerOrderId));
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
}
