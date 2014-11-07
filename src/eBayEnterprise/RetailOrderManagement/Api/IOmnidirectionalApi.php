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

namespace eBayEnterprise\RetailOrderManagement\Api;

use eBayEnterprise\RetailOrderManagement\Payload;

/**
 * Interface IOmnidirectionalApi
 * @package eBayEnterprise\RetailOrderManagement\Api
 *
 * A generic api object that fetches messages as payload objects.
 */
interface IOmnidirectionalApi
{
    /**
     * Connect to the queue and retrieve messages to be processed.
     * Fetch may be eager and get all messages immediately or lazy and only
     * actually get messages as they are processed.
     *
     * @return \Iterator
     * @throws Exception\ConnectionError
     */
    public function fetch();
}
