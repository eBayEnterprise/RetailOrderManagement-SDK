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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class CustomizationInstruction implements ICustomizationInstruction
{
    use TPayload;

    const ROOT_NODE = 'Instructions';

    /** @var string */
    protected $key;
    /** @var string */
    protected $value;
    /** @var string */
    protected $displayTitle;
    /** @var string */
    protected $displayValue;

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
            'key' => 'string(x:Key)',
            'value' => 'string(x:Value)',
        ];
        $this->optionalExtractionPaths = [
            'displayTitle' => 'x:DisplayTitle',
            'displayValue' => 'x:DisplayValue',
        ];
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setKey($key)
    {
        $this->key = $this->cleanString($key, 20);
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setValue($value)
    {
        $this->value = $this->cleanString($value, 254);
        return $this;
    }

    public function getDisplayTitle()
    {
        return $this->displayTitle;
    }

    public function setDisplayTitle($displayTitle)
    {
        $this->displayTitle = $this->cleanString($displayTitle, 254);
        return $this;
    }

    public function getDisplayValue()
    {
        return $this->displayValue;
    }

    public function setDisplayValue($displayValue)
    {
        $this->displayValue = $this->cleanString($displayValue, 254);
        return $this;
    }

    protected function serializeContents()
    {
        return "<Key>{$this->xmlEncode($this->getKey())}</Key>"
            . "<Value>{$this->xmlEncode($this->getValue())}</Value>"
            . $this->serializeOptionalXmlEncodedValue('DisplayTitle', $this->getDisplayTitle())
            . $this->serializeOptionalXmlEncodedValue('DisplayValue', $this->getDisplayValue());
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
