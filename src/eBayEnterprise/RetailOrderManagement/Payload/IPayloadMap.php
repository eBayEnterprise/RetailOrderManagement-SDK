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

interface IPayloadMap
{
    /**
     * Given an abstract type, return the name of a concrete type.
     * @param string $abstractType
     * @return string
     * @throws Exception\UnsupportedPayload If a concrete type cannot be found for the abstract type
     */
    public function getConcreteType($abstractType);

    /**
     * Merge a payload mapping with this one. Merged mappings override/come before
     * this mapping.
     * @param IPayloadMap $payloadMap
     * @return self
     */
    public function merge(IPayloadMap $payloadMap);

    /**
     * Check if the payload mapping knows about an abstract type.
     * @param string $abstractType
     * @return bool
     */
    public function hasMappingForType($abstractType);
}
