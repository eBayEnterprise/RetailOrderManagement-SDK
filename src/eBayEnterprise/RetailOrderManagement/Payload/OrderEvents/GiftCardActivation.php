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
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class GiftCardActivation implements IGiftCardActivation
{
    use TPayload, TAmount;

    /** @var string */
    protected $giftCardAction;
    /** @var string */
    protected $denomination;
    /** @var string */
    protected $giftCardCode;
    /** @var string */
    protected $giftCardSecondaryCode;
    /** @var string */
    protected $giftCardPin;
    /** @var string */
    protected $giftCardFaceIdentifier;
    /** @var string */
    protected $to;
    /** @var string */
    protected $from;
    /** @var string */
    protected $message;

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

        $this->optionalExtractionPaths = [
            'giftCardAction' => '@giftCertificateAction',
            'denomination' => '@denomination',
            'giftCardCode' => '@giftCertCode',
            'giftCardSecondaryCode' => '@giftCertSecondaryCode',
            'giftCardPin' => '@giftCertPin',
            'giftCardFaceIdentifier' => '@giftCertFaceIdentifier',
            'to' => '@to',
            'from' => '@from',
            'message' => '@message',
        ];
    }

    public function getGiftCardAction()
    {
        return $this->giftCardAction;
    }

    public function setGiftCardAction($giftCardAction)
    {
        $this->giftCardAction = $giftCardAction;
        return $this;
    }

    public function getDenomination()
    {
        return $this->denomination;
    }

    public function setDenomination($denomination)
    {
        $this->denomination = $this->sanitizeAmount($denomination);
        return $this;
    }

    public function getGiftCardCode()
    {
        return $this->giftCardCode;
    }

    public function setGiftCardCode($giftCardCode)
    {
        $this->giftCardCode = $giftCardCode;
        return $this;
    }

    public function getGiftCardSecondaryCode()
    {
        return $this->giftCardSecondaryCode;
    }

    public function setGiftCardSecondaryCode($giftCardSecondaryCode)
    {
        $this->giftCardSecondaryCode = $giftCardSecondaryCode;
        return $this;
    }

    public function getGiftCardPin()
    {
        return $this->giftCardPin;
    }

    public function setGiftCardPin($giftCardPin)
    {
        $this->giftCardPin = $giftCardPin;
        return $this;
    }

    public function getGiftCardFaceIdentifier()
    {
        return $this->giftCardFaceIdentifier;
    }

    public function setGiftCardFaceIdentifier($giftCardFaceIdentifier)
    {
        $this->giftCardFaceIdentifier = $giftCardFaceIdentifier;
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return '';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function getRootAttributes()
    {
        $denomination = $this->getDenomination();
        return array_filter([
            'giftCertificateAction' => $this->getGiftCardAction(),
            'denomination' => $denomination ? $this->formatAmount($denomination) : null,
            'giftCertCode' => $this->getGiftCardCode(),
            'giftCertSecondaryCode' => $this->getGiftCardSecondaryCode(),
            'giftCertPin' => $this->getGiftCardPin(),
            'giftCertFaceIdentifier' => $this->getGiftCardFaceIdentifier(),
            'to' => $this->getTo(),
            'from' => $this->getFrom(),
            'message' => $this->getMessage(),
        ]);
    }
}
