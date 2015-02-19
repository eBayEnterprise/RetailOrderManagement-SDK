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
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class ShippedOrderItem extends OrderItem implements IShippedOrderItem
{
    use TShippedItem, TProductPrice, TShopRunnerMessage, TTrackingNumberContainer, TAmount {
        TAmount::serializeAmount insteadof TProductPrice;
        TAmount::sanitizeAmount insteadof TProductPrice;
    }

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
        parent::__construct($validators, $schemaValidator, $payloadMap, $logger, $parentPayload);

        $this->logger = $logger;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new PayloadFactory();

        $this->trackingNumbers = $this->buildPayloadForInterface(static::TRACKING_NUMBER_ITERABLE_INTERFACE);

        // extract parent data as well as additional data needed
        // for the subclass
        $this->extractionPaths = array_merge(
            $this->extractionPaths,
            [
                'shippedQuantity' => 'number(@shippedQuantity)',
                'shopRunnerMessage' => 'string(x:ShopRunnerMessage)',
            ]
        );
        $this->optionalExtractionPaths = array_merge(
            $this->optionalExtractionPaths,
            [
                'amount' => 'x:Pricing/x:Amount',
                'unitPrice' => 'x:Pricing/x:UnitPrice',
                'remainder' => 'x:Pricing/x:Amount/@remainder',
            ]
        );
        $this->subpayloadExtractionPaths = array_merge(
            $this->subpayloadExtractionPaths,
            [
                'trackingNumbers' => 'x:TrackingNumberList',
            ]
        );
    }

    protected function getRootAttributes()
    {
        return array_merge(
            parent::getRootAttributes(),
            ['shippedQuantity' => $this->getShippedQuantity()]
        );
    }

    protected function serializeContents()
    {
        return parent::serializeContents()
            . $this->serializeProductPrice()
            . $this->getTrackingNumbers()->serialize()
            . $this->serializeShopRunnerMessage();
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
