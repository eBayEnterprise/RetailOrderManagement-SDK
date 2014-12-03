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
use SplStack;

class PayloadMap implements IPayloadMap
{
    protected $classMap = [];
    /** @var SplStack */
    protected $overrides;

    public function __construct(array $classMap = [])
    {
        $this->classMap = $classMap;
        $this->overrides = new SplStack;
    }

    public function getConcreteType($abstractType)
    {
        $override = $this->getOverrideWithMapping($abstractType);
        if ($override) {
            return $override->getConcreteType($abstractType);
        }
        if (isset($this->classMap[$abstractType])) {
            return $this->classMap[$abstractType];
        }
        throw new UnsupportedPayload("No concrete type known for '$abstractType'");
    }

    /**
     * Get the first override payload map that has a mapping for the given
     * abstract type. If none of the overrides have a mapping, returns null.
     * @param string $abstractType
     * @return IPayloadMap|null
     */
    protected function getOverrideWithMapping($abstractType)
    {
        foreach ($this->overrides as $overrideMap) {
            if ($overrideMap->hasMappingforType($abstractType)) {
                return $overrideMap;
            }
        }
        return null;
    }

    public function merge(IPayloadMap $payloadMap)
    {
        $this->overrides->push($payloadMap);
    }

    public function hasMappingForType($abstractType)
    {
        return $this->getOverrideWithMapping($abstractType) || isset($this->classMap[$abstractType]);
    }
}
