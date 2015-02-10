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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IShipGroup extends IPayload, IOrderItemReferenceContainer, IGifting
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Unique identifier for the ship group.
     *
     * @return string
     */
    public function getId();

    /**
     * @param string
     * @return self
     */
    public function setId($id);

    /**
     * Type of shipping charge. Typically "FLAT" or "WEIGHT".
     *
     * @return string
     */
    public function getChargeType();

    /**
     * @param string
     * @return self
     */
    public function setChargeType($chargeType);

    /**
     * Destination for the ship group.
     *
     * @return IDestination
     */
    public function getDestination();

    /**
     * @param IDestination
     * @return self
     */
    public function setDestination(IDestination $destination);

    /**
     * Return the id of the destination referenced by the ship group.
     *
     * @return string
     */
    public function getDestinationId();
}
