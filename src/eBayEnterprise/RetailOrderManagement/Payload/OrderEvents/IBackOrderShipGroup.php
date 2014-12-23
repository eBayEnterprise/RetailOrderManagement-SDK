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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IBackOrderShipGroup extends IShipGroup
{
    const EDD_MESSAGE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\IEddMessage';

    /**
     * The estimated ship date
     * @return DateTime | null
     */
    public function getEstimatedShipDate();

    /**
     * @param string
     * @return self
     */
    public function setEstimatedShipDate($date);

    /**
     * The estimated delivery date (EDD)
     * @return IEddMessage
     */
    public function getEddMessage();

    /**
     * @param IEddMessage
     * @return self
     */
    public function setEddMessage(IEddMessage $message);
}
