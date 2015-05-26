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

interface IChargeGroup extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'ChargeGroup';
    const REFERENCE_CHARGES_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IReferencedCharge';
    const ADJUSTMENT_CHARGES_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IAdjustmentCharge';

    /**
     * @return string
     */
    public function getName();

    /**
     * @param  string
     * @return self
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getAdjustmentCategory();

    /**
     * @param  string
     * @return self
     */
    public function setAdjustmentCategory($adjustmentCategory);

    /**
     * @return IReferencedCharge
     */
    public function getReferencedCharge();

    /**
     * @param  IReferencedCharge
     * @return self
     */
    public function setReferencedCharge(IReferencedCharge $referencedCharge);

    /**
     * @return IAdjustmentCharge
     */
    public function getAdjustmentCharge();

    /**
     * @param  IAdjustmentCharge
     * @return self
     */
    public function setAdjustmentCharge(IAdjustmentCharge $adjustmentCharge);
}
