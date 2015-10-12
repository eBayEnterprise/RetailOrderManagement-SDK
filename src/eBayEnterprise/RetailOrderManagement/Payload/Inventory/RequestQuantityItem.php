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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;

class RequestQuantityItem extends QuantityItem implements IRequestQuantityItem
{
    /** @var string */
    protected $fulfillmentLocationType;
    /** @var string */
    protected $fulfillmentLocationId;

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
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'itemId' => 'string(@itemId)',
            'id' => 'string(@lineId)',
        ];
        $this->optionalExtractionPaths = [
            'fulfillmentLocationId' => 'x:FulfillmentLocationId',
            'fulfillmentLocationType' => 'x:FulfillmentLocationId/@type',
        ];
    }

    /**
     * Type of the requested fulfillment location.
     *
     * restrictions: optional, only supports type of "ISPU"
     * @param string
     */
    public function getFulfillmentLocationType()
    {
        return $this->fulfillmentLocationType;
    }

    /**
     * @param string
     * @return self
     */
    public function setFulfillmentLocationType($fulfillmentLocationType)
    {
        $this->fulfillmentLocationType = $fulfillmentLocationType;
        return $this;
    }

    /**
     * Identified for the requested fulfillment location.
     *
     * restrictions: optional, 1 <= length <= 100
     * @return string
     */
    public function getFulfillmentLocationId()
    {
        return $this->fulfillmentLocationId;
    }

    /**
     * @param string
     * @return self
     */
    public function setFulfillmentLocationId($fulfillmentLocationId)
    {
        $this->fulfillmentLocationId = $fulfillmentLocationId;
        return $this;
    }

    protected function serializeContents()
    {
        $locationId = $this->getFulfillmentLocationId();
        if ($locationId) {
            $typeAttribute = $this->serializeOptionalAttribute(
                'type',
                $this->xmlEncode($this->getFulfillmentLocationType())
            );
            return "<FulfillmentLocationId $typeAttribute>{$this->xmlEncode($locationId)}</FulfillmentLocationId>";
        }
        return '';
    }
}
