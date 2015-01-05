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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

trait TLineItemContainer
{
    /** @var ILineItemContainer * */
    protected $lineItems;

    /**
     * Serialization of line items
     * @return string
     */
    protected function serializeLineItems()
    {
        return count($this->getLineItems()) ? $this->getLineItems()->serialize() :'';
    }

    /**
     * Get an iterable of the line items for this container.
     *
     * @return ILineItemIterable
     */
    public function getLineItems()
    {
        return $this->lineItems;
    }

    /**
     * @param ILineItemIterable
     * @return self
     */
    public function setLineItems(ILineItemIterable $items)
    {
        $this->lineItems = $items;
        return $this;
    }
}
