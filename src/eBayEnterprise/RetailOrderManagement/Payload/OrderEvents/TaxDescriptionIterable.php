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

use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use SPLObjectStorage;

class TaxDescriptionIterable extends SPLObjectStorage implements ITaxDescriptionIterable
{
    use TIterablePayload;
    // This most likely needs to be re-defined by more specific types used by
    // the various types of order items.
    const ROOT_NODE = 'OrderItems';

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator unused, kept to allow IPayloadMap to be passed
     * @param IPayloadMap
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap
    ) {
        $this->validators = $validators;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new PayloadFactory();
    }

    public function getEmptyTaxDescription()
    {
        return $this->payloadFactory->buildPayload(
            $this->payloadMap->getConcreteType(static::TAX_DESCRIPTION_INTERFACE),
            $this->payloadMap
        );
    }

    protected function getNewSubpayload()
    {
        return $this->getEmptyTaxDescription();
    }

    protected function getSubpayloadXPath()
    {
        return static::SUBPAYLOAD_XPATH;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }
}
