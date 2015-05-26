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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use DateTime;

interface IOrderDetailShippingResponse extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'Shipping';

    /**
     * @return IShipGroupIterable
     */
    public function getShipGroups();

    /**
     * @param  IShipGroupIterable
     * @return self
     */
    public function setShipGroups(IShipGroupIterable $shipGroups);

    /**
     * @return IDestinationIterable
     */
    public function getDestinations();

    /**
     * @param  IDestinationIterable
     * @return self
     */
    public function setDestinations(IDestinationIterable $destinations);

    /**
     * @return IShipmentIterable
     */
    public function getShipments();

    /**
     * @param  IShipmentIterable
     * @return self
     */
    public function setShipments(IShipmentIterable $shipments);
}
