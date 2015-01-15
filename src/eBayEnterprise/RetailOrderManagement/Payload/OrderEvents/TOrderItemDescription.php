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

use eBayEnterprise\RetailOrderManagement\Payload\Order\TOrderItemDescription as TBaseOrderItemDescription;

trait TOrderItemDescription
{
    use TBaseOrderItemDescription;

    /** @var string */
    protected $title;

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Serialize the order item description as XML
     *
     * @return string
     */
    protected function serializeOrderItemDescription()
    {
        //
        return $this->hasItemDescription()
            ? sprintf(
                '<Description>%s<Title>%s</Title>%s%s</Description>',
                $this->serializeProductDescription(),
                $this->getTitle(),
                $this->serializeColor(),
                $this->serializeSize()
            )
            : '';
    }

    /**
     * Is there sufficient data to include an order item description. Must
     * have at least a title and description.
     *
     * @return bool
     */
    protected function hasItemDescription()
    {
        return $this->getTitle() && $this->getDescription();
    }
}
