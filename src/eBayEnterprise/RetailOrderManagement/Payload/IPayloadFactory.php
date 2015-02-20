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

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

interface IPayloadFactory
{
    /**
     * Construct a new payload instance
     * @param  string
     * @param  IPayloadMap | null
     * @param  IPayload | null
     * @param  LoggerInterface
     * @return IPayload
     */
    public function buildPayload($type, IPayloadMap $cascadedPayloadMap = null, IPayload $parentPayload = null, LoggerInterface $logger = null);
}
