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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;

use Psr\Log\LoggerInterface;

abstract class AbstractCustomization
{
    use TPayload, TCustomizationBase;

    const ROOT_NODE = 'CustomFeature';

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;

        $this->optionalExtractionPaths = [
            'customizationId' => 'x:CustomizationId',
            'itemId' => 'x:ItemId',
            'itemDescription' => 'x:ItemDesc',
            'id' => '@id',
        ];
    }

    public function getEmptyPriceGroup()
    {
        return $this->buildPayloadForInterface(static::PRICE_GROUP_INTERFACE);
    }

    /**
     * @return IMerchandisePriceGroup|ItaxedMerchandisePriceGroup
     */
    abstract public function getUpCharge();

    protected function serializeContents()
    {
        return $this->serializeOptionalXmlEncodedValue('CustomizationId', $this->getCustomizationId())
            . $this->serializeOptionalXmlEncodedValue('ItemId', $this->getItemId())
            . $this->serializeOptionalXmlEncodedValue('ItemDesc', $this->getItemDescription())
            . (!is_null($this->getUpCharge()) ? $this->getUpCharge()->setRootNodeName('Upcharge')->serialize() : '');
    }

    protected function deserializeExtra($serializedPayload)
    {
        $xpath = $this->getPayloadAsXPath($serializedPayload);
        $priceNode = $xpath->query('x:Upcharge')->item(0);
        if ($priceNode) {
            $this->upCharge = $this->getEmptyPriceGroup()->deserialize($priceNode->C14N());
        }
    }

    protected function getRootAttributes()
    {
        return $this->getId() ? ['id' => $this->getId()] : [];
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
