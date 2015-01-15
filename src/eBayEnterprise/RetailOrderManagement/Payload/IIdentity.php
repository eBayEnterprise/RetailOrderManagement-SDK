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

namespace eBayEnterprise\RetailOrderManagement\Payload;

interface IIdentity
{
    /**
     * Get a unique id for the item. If one is not yet set, will be a generated
     * unique id.
     *
     * @return string
     */
    public function getId();

    /**
     * Set the id of the item. This must be unique across all related payloads,
     * (payloads with a shared ancestor regardless of type). Unless necessary
     * to maintain existing id references, it is best to allow this to be generated.
     *
     * @param string
     * @return self
     */
    public function setId($id);
}
