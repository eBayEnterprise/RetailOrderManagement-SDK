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

use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;

/**
 * Allocation Status of an item
 */
class AllocatedItem implements IAllocatedItem
{
    use TPayload, TAmount, TItem;

    const ROOT_NODE = 'AllocationResponse';

    /** @var int */
    protected $amountAllocated;

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
        list(
            $this->validators,
            $this->schemaValidator,
            $this->payloadMap,
            $this->logger,
            $this->parentPayload
        ) = func_get_args();

        $this->extractionPaths = [
            'itemId' => 'string(@itemId)',
            'id' => 'string(@lineId)',
            'amountAllocated' => 'string(x:AmountAllocated)'
        ];
    }

    /**
     * Quantity of the items actually allocated for the order
     *
     * restrictions: optional
     * @return int
     */
    public function getAmountAllocated()
    {
        return $this->amountAllocated;
    }

    /**
     * @param int
     * @return self
     */
    public function setAmountAllocated($amountAllocated)
    {
        $this->amountAllocated = $amountAllocated;
        return $this;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::serializeContents
     * @return string
     */
    protected function serializeContents()
    {
        $quantity = (int) $this->sanitizeAmount($this->getAmountAllocated());
        return "<AmountAllocated>$quantity</AmountAllocated>";
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::getRootAttributes
     * @return array
     */
    protected function getRootAttributes()
    {
        return ['lineId' => $this->getId(), 'itemId' => $this->getItemId()];
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::getRootNodeName
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * @see eBayEnterprise\RetailOrderManagement\Payload\TPayload::getXmlNamespace
     * @param string
     * @return self
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }
}
