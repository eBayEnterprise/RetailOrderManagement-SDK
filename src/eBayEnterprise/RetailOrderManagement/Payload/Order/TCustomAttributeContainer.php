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

trait TCustomAttributeContainer
{
    /** @var ICustomAttributeIterable */
    protected $customAttributes;

    public function getCustomAttributes()
    {
        return $this->customAttributes;
    }

    public function setCustomAttributes(ICustomAttributeIterable $customAttributes)
    {
        $this->customAttributes = $customAttributes;
        return $this;
    }
}
