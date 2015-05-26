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

interface IStatus extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'Status';

    /**
     * @return string
     */
    public function getQuantity();

    /**
     * @param  string
     * @return self
     */
    public function setQuantity($quantity);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param  string
     * @return self
     */
    public function setStatus($status);

    /**
     * @return DateTime
     */
    public function getStatusDate();

    /**
     * @param  DateTime
     * @return self
     */
    public function setStatusDate(DateTime $statusDate);

    /**
     * @return DateTime
     */
    public function getExpectedShipmentDate();

    /**
     * @param  DateTime
     * @return self
     */
    public function setExpectedShipmentDate(DateTime $expectedShipmentDate);

    /**
     * @return DateTime
     */
    public function getExpectedDeliveryDate();

    /**
     * @param  DateTime
     * @return self
     */
    public function setExpectedDeliveryDate(DateTime $expectedDeliveryDate);

    /**
     * @return DateTime
     */
    public function getProductAvailabilityDate();

    /**
     * @param  DateTime
     * @return self
     */
    public function setProductAvailabilityDate(DateTime $productAvailabilityDate);

    /**
     * @return string
     */
    public function getWarehouse();

    /**
     * @param  string
     * @return self
     */
    public function setWarehouse($warehouse);
}
