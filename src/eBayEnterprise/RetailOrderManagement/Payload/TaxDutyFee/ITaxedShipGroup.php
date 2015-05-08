<?php
/**
 * Copyright (c) 2014-2015 eBay Enterprise, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * @copyright   Copyright (c) 2014-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IDestination as ICheckoutDestination;

interface ITaxedShipGroup extends IPayload, IIdentity, ITaxedOrderItemContainer, ITaxedGifting
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Get a unique id for the ship group. If one is not yet set, It will be a
     * generated unique id.
     *
     * @return string
     */
    public function getId();

    /**
     * Set the id of the ship group. This must be unique across all related payloads,
     * (payloads with a shared ancestor regardless of type). Unless necessary
     * to maintain existing id references, it is best to allow this to be generated.
     *
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
     * @return ICheckoutDestination
     */
    public function getDestination();

    /**
     * @param ICheckoutDestination
     * @return self
     */
    public function setDestination(ICheckoutDestination $destination);

    /**
     * Return the id of the destination referenced by the ship group.
     *
     * @return string
     */
    public function getDestinationId();
}
