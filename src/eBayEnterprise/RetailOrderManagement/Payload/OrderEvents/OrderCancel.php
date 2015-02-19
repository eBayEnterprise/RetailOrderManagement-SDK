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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderCancel implements IOrderCancel
{
    use TTopLevelPayload, TLoyaltyProgramCustomer, TOrderEvent, TOrderItemContainer, TCurrency;

    /** @var string */
    protected $cancelReason;
    /** @var string */
    protected $cancelReasonCode;

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
        $this->payloadFactory = new PayloadFactory();

        $this->loyaltyPrograms =
            $this->buildPayloadForInterface(static::LOYALTY_PROGRAM_ITERABLE_INTERFACE);
        $this->orderItems =
            $this->buildPayloadForInterface(static::ORDER_ITEM_ITERABLE_INTERFACE);

        $this->extractionPaths = [
            'customerFirstName' => 'string(x:Customer/x:Name/x:FirstName)',
            'customerLastName' => 'string(x:Customer/x:Name/x:LastName)',
            'storeId' => 'string(@storeId)',
            'orderId' => 'string(@customerOrderId)',
            'currencyCode' => 'string(@currency)',
            'currencySymbol' => 'string(@currencySymbol)',
            'cancelReason' => 'string(x:OrderCancelReason)',
            'cancelReasonCode' => 'string(x:OrderCancelReason/@cancelReasonCode)',
        ];
        $this->optionalExtractionPaths = [
            'customerId' => 'x:Customer/@customerId',
            'customerMiddleName' => 'x:Customer/x:Name/x:MiddleName',
            'customerHonorificName' => 'x:Customer/x:Name/x:Honorific',
            'customerEmailAddress' => 'x:Customer/x:EmailAddress',
        ];
        $this->subpayloadExtractionPaths = [
            'loyaltyPrograms' => 'x:Customer/x:LoyaltyPrograms',
            'orderItems' => 'x:CancelledOrderItems',
        ];
    }

    public function getCancelReason()
    {
        return $this->cancelReason;
    }

    public function setCancelReason($cancelReason)
    {
        $this->cancelReason = $cancelReason;
        return $this;
    }

    public function getCancelReasonCode()
    {
        return $this->cancelReasonCode;
    }

    public function setCancelReasonCode($cancelReasonCode)
    {
        $this->cancelReasonCode = $cancelReasonCode;
        return $this;
    }

    public function getEventType()
    {
        return static::ROOT_NODE;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    protected function serializeContents()
    {
        return $this->serializeCustomer()
            . $this->serializeCancelReason()
            . $this->getOrderItems()->serialize();
    }

    protected function serializeCancelReason()
    {
        return sprintf(
            '<OrderCancelReason cancelReasonCode="%s">%s</OrderCancelReason>',
            $this->getCancelReasonCode(),
            $this->getCancelReason()
        );
    }
    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'storeId' => $this->getStoreId(),
            'customerOrderId' => $this->getCustomerOrderId(),
            'currency' => $this->getCurrencyCode(),
            'currencySymbol' => $this->getCurrencySymbol(),
        ];
    }
}
