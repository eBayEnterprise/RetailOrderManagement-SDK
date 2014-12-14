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

use DateTime;

trait TDeliveryWindow
{
    /** @var DateTime */
    protected $from;
    /** @var DateTime */
    protected $to;

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = new DateTime($from);
        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to)
    {
        $this->to = new DateTime($to);
        return $this;
    }

    protected function serializeDeliveryWindow()
    {
        $from = $this->getFrom();
        $to = $this->getTo();
        return ($from && $to)
            ? "<DeliveryWindow><From>{$from->format('c')}</From><To>{$to->format('c')}</To></DeliveryWindow>" : '';
    }
}
