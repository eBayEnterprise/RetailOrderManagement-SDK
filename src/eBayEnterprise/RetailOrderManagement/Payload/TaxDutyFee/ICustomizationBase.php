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

interface ICustomizationBase extends IPayload
{
    /**
     * unique identifier for the customization
     *
     * restrictions: optional, length < 12
     * @return string
     */
    public function getId();

    /**
     * @param string
     * @return self
     */
    public function setId($id);

    /**
     * Identifier used to group customizations instructions into logical sets.
     *
     * restrictions: optional
     * @return int
     */
    public function getCustomizationId();

    /**
     * @param int
     * @return self
     */
    public function setCustomizationId($customizationId);

    /**
     * Item id used to specify the customization. May be for a physical
     * item or an accounting placeholder value.
     *
     * restrictions: optional, string with length >= 1 and <= 20
     * @return string
     */
    public function getItemId();

    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId);

    /**
     * Description of an inventory item.
     *
     * restrictions: optional
     * @return string
     */
    public function getItemDescription();

    /**
     * @param string
     * @return self
     */
    public function setItemDescription($description);
}
