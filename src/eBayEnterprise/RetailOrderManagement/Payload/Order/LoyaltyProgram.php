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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class LoyaltyProgram implements ILoyaltyProgram
{
    use TPayload, TCustomAttributeContainer;

    /** @var string */
    protected $account;
    /** @var string */
    protected $program;

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
        $this->payloadFactory = new PayloadFactory;

        $this->extractionPaths = [
            'account' => 'string(x:Account)',
            'program' => 'string(x:Program)',
        ];
        $this->subpayloadExtractionPaths = [
            'customAttributes' => 'x:CustomAttributes',
        ];

        $this->customAttributes = $this->buildPayloadForInterface(static::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE);
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount($account)
    {
        $this->account = $account;
        return $this;
    }

    public function getProgram()
    {
        return $this->program;
    }

    public function setProgram($program)
    {
        $this->program = $program;
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return sprintf(
            '<Account>%s</Account><Program>%s</Program>%s',
            $this->xmlEncode($this->getAccount()),
            $this->xmlEncode($this->getProgram()),
            $this->getCustomAttributes()->serialize()
        );
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
