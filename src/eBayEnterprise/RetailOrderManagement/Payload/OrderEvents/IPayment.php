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

interface IPayment extends IPayload
{
    const ROOT_NODE = 'Payment';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * Description of the payment method.
     *
     * @return string
     */
    public function getDescription();
    /**
     * @param string
     * @return self
     */
    public function setDescription($description);
    /**
     * Tender type of the payment method.
     *
     * @return string
     */
    public function getTenderType();
    /**
     * @param string
     * @return self
     */
    public function setTenderType($tenderType);
    /**
     * Payment account, masked to remove sensitive data.
     *
     * @return string
     */
    public function getMaskedAccount();
    /**
     * @param string
     * @return self
     */
    public function setMaskedAccount($maskedAccount);
    /**
     * Amount applied to the payment method.
     *
     * @return float
     */
    public function getAmount();
    /**
     * @param float
     * @return self
     */
    public function setAmount($amount);
}
