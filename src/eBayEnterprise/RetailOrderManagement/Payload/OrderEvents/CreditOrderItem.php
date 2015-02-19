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
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CreditOrderItem extends OrderItem implements ICreditOrderItem
{
    use TAmount;

    /** @var float */
    protected $amount;
    /** @var string */
    protected $reason;
    /** @var string */
    protected $reasonCode;
    /** @var float */
    protected $remainingQuantity;

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
        // extract parent data as well as additional data needed
        // for the subclass
        $this->extractionPaths = array_merge(
            $this->extractionPaths,
            [
                'remainingQuantity' => 'number(@remainingQuantity)',
                'amount' => 'number(x:Pricing/x:AdjustedAmount)',
                'OrderReturnReason' => 'string(x:OrderReturnReason)',
                'reason' => 'string(x:OrderReturnReason)',
                'reasonCode' => 'string(x:OrderReturnReason/@reasonCode)'
            ]
        );
    }

    /**
     * Attributes in addition to those in OrderItem
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return array_merge(
            parent::getRootAttributes(),
            ['remainingQuantity' => $this->getRemainingQuantity()]
        );
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getReason()
    {
        return $this->reason;
    }

    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    public function getReasonCode()
    {
        return $this->reasonCode;
    }

    public function setReasonCode($reasonCode)
    {
        $this->reasonCode = $reasonCode;
        return $this;
    }

    public function getRemainingQuantity()
    {
        return $this->remainingQuantity;
    }

    public function setRemainingQuantity($remainingQuantity)
    {
        $this->remainingQuantity = $remainingQuantity;
        return $this;
    }

    protected function serializeContents()
    {
        return parent::serializeContents()
        . $this->serializeAdjustedPrice()
        . $this->serializeOrderReturnReason();
    }

    protected function serializeAdjustedPrice()
    {
        return '<Pricing>'
        . $this->serializeAmount('AdjustedAmount', $this->getAmount())
        . '</Pricing>';
    }

    protected function serializeOrderReturnReason()
    {
        $format = '<OrderReturnReason reasonCode="%s">%s</OrderReturnReason>';
        return sprintf(
            $format,
            $this->getReasonCode(),
            $this->getReason()
        );
    }
}
