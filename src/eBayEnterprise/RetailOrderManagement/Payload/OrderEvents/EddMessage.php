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

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EddMessage implements IEddMessage
{
    use TPayload, TDeliveryWindow;

    /** @var string */
    protected $mode;
    /** @var string */
    protected $messageType;
    /** @var string */
    protected $template;

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
            'mode' => 'string(x:Mode)',
            'messageType' => 'string(x:MessageType)',
            'template' => 'string(x:Template)',
        ];
        $this->optionalExtractionPaths = [
            'to' => 'x:DeliveryWindow/x:To',
            'from' => 'x:DeliveryWindow/x:From',
        ];
    }

    /**
     * @param string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function deserializeExtra($serializedPayload)
    {
        $from = $this->getFrom();
        $to = $this->getTo();
        if (!$from instanceof DateTime && !empty($from) && is_string($from)) {
            $this->setFrom($from);
        }
        if (!$to instanceof DateTime && !empty($to) && is_string($to)) {
            $this->setTo($to);
        }
        return $this;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    public function getMessageType()
    {
        return $this->messageType;
    }

    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;
        return $this;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getRootAttributes()
    {
        return [];
    }

    protected function serializeContents()
    {
        return $this->serializeMode()
            . $this->serializeMessageType()
            . $this->serializeTemplate()
            . $this->serializeDeliveryWindow();
    }

    protected function serializeMode()
    {
        $mode = $this->getMode();
        return (!empty($mode) && is_string($mode))
            ? "<Mode>$mode</Mode>" : '';
    }

    protected function serializeMessageType()
    {
        $messageType = $this->getMessageType();
        return (!empty($messageType) && is_string($messageType))
            ? "<MessageType>$messageType</MessageType>" : '';
    }

    protected function serializeTemplate()
    {
        $template = $this->getTemplate();
        return (!empty($template) && is_string($template))
            ? "<Template>$template</Template>" : '';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
