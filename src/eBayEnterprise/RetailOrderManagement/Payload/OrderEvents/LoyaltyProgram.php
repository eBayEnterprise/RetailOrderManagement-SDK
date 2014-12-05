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

use eBayEnterprise\RetailOrderManagement\Payload\ICustomAttributesIterable;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

class LoyaltyProgram implements ILoyaltyProgram
{
    use TPayload, TCustomAttributeContainer;

    /** @var IPayloadMap */
    protected $payloadMap;
    /** @var PayloadFactory */
    protected $payloadFactory;
    /** @var string */
    protected $account;
    /** @var string */
    protected $program;
    /** @var ICustomAttributeIterable */
    protected $customAttributes;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap
    ) {
        $this->extractionPaths = [
            'account' => 'string(Account)',
            'program' => 'string(Program)',
        ];
        $this->subpayloadExtractionPaths = [
            'customAttributes' => 'CustomAttributes',
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new PayloadFactory;

        $this->customAttributes = $this->payloadFactory->buildPayload(
            $this->payloadMap->getConcreteType(static::CUSTOM_ATTRIBUTE_ITERABLE_INTERFACE),
            $this->payloadMap
        );
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
            $this->getAccount(),
            $this->getProgram(),
            $this->getCustomAttributes()->serialize()
        );
    }
}
