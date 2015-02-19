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

use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TTopLevelPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderBackorder implements IOrderBackorder
{
    use TTopLevelPayload, TLoyaltyProgramCustomer, TOrderEvent, TShipGroupContainer, TCurrency;

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
        $this->shipGroups =
            $this->buildPayloadForInterface(static::SHIP_GROUP_ITERABLE_INTERFACE);

        $this->extractionPaths = [
            'customerFirstName' => 'string(x:Customer/x:Name/x:FirstName)',
            'customerLastName' => 'string(x:Customer/x:Name/x:LastName)',
            'storeId' => 'string(@storeId)',
            'orderId' => 'string(@customerOrderId)',
            'currencyCode' => 'string(@currency)',
            'currencySymbol' => 'string(@currencySymbol)',
        ];
        $this->optionalExtractionPaths = [
            'customerId' => 'x:Customer/@customerId',
            'customerMiddleName' => 'x:Customer/x:Name/x:MiddleName',
            'customerHonorificName' => 'x:Customer/x:Name/x:Honorific',
            'customerEmailAddress' => 'x:Customer/x:EmailAddress',
        ];
        $this->subpayloadExtractionPaths = [
            'loyaltyPrograms' => 'x:Customer/x:LoyaltyPrograms',
            'shipGroups' => 'x:ShipGroups',
        ];
    }

    public function getEventType()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'customerOrderId' => $this->getCustomerOrderId(),
            'storeId' => $this->getStoreId(),
            'currency' => $this->getCurrencyCode(),
            'currencySymbol' => $this->getCurrencySymbol(),
        ];
    }

    protected function getSchemaFile()
    {
        return $this->getSchemaDir() . self::XSD;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        $contents = $this->serializeCustomer() . $this->getShipGroups()->serialize();
        if ($this->logger) {
            $message = 'Serializing the backorder event payload';
            $context = [
                'event_type' => $this->getEventType(),
                'class' => __CLASS__,
                'paypload' => $contents
            ];
            $this->logger->debug($message, $context);
        }
        return $contents;
    }
}
