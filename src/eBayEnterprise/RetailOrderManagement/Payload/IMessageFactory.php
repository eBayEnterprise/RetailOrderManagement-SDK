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

interface IMessageFactory
{
    /**
     * Returns a new payload object for a type of message. Only one type of
     * message may be returned from a single message factory.
     * @param string $type Unique message type to resolve to a message payload type
     * @return IPayload
     * @throws Exception\UnsupportedPayload If no payload can be found for the type.
     */
    public function messagePayload($type);
}
