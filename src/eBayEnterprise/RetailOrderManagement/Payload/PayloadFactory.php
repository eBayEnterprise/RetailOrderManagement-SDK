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

namespace eBayEnterprise\RetailOrderManagement\Payload;

use eBayEnterprise\RetailOrderManagement\Payload\Exception\UnsupportedPayload;
use eBayEnterprise\RetailOrderManagement\Payload\ConfigLocator;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Abstract factory implementation for building payload objects.
 */
class PayloadFactory implements IPayloadFactory
{
    /** @var ILocator Maps a payload type to configuration used to construct the payload */
    protected $locator;

    /**
     * @param ILocator
     */
    public function __construct(ILocator $locator = null)
    {
        // If no custom payload locator is used, default to a locator using
        // the PayloadConfigMap.
        $this->locator = $locator ?: new ConfigMapLocator;
    }

    /**
     * Build a payload using the payload config mappings.
     * @param string $type Concrete payload type
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @return IPayload
     * @throws UnsupportedPayload If the payload type is not configured
     */
    public function buildPayload($type, IPayloadMap $cascadedPayloadMap = null, IPayload $parentPayload = null, LoggerInterface $logger = null)
    {
        if ($this->locator->hasPayloadConfig($type)) {
            $payloadConfig = $this->locator->getPayloadConfig($type);

            $validatorIteratorConfig = $payloadConfig['validatorIterator'];
            $validatorsConfig = $payloadConfig['validators'];
            $schemaValidatorConfig = $payloadConfig['schemaValidator'];
            $payloadMapConfig = $payloadConfig['childPayloads']['payloadMap'];
            $childPayloadsConfig = $payloadConfig['childPayloads']['types'];

            $validatorIterator = new $validatorIteratorConfig($this->buildValidators($validatorsConfig));
            $schemaValidator = new $schemaValidatorConfig();
            /** @var IPayloadMap $payloadMap */
            $payloadMap = new $payloadMapConfig($childPayloadsConfig);
            if ($cascadedPayloadMap) {
                $payloadMap->merge($cascadedPayloadMap);
            }

            return new $type($validatorIterator, $schemaValidator, $payloadMap, $logger ?: new NullLogger(), $parentPayload);
        }
        throw new UnsupportedPayload("No configuration found for '$type'");
    }

    /**
     * Instantiate each validator and store it in an array
     * to be passed to IValidatorIterator
     *
     * @param $validators
     * @return array
     */
    protected function buildValidators($validators)
    {
        return array_map(
            function ($validatorConfig) {
                return new $validatorConfig['validator']($validatorConfig['params']);
            },
            $validators
        );
    }
}
