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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ILoyaltyProgram extends IPayload, ICustomAttributeContainer
{
    const ROOT_NODE = 'LoyaltyProgram';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * The customer account with which the loyalty program is linked.
     *
     * @return string
     */
    public function getAccount();

    /**
     * @param string
     * @return self
     */
    public function setAccount($account);

    /**
     * The name of the loyalty program, for example "Smart Shopper" or "Buyer's Club"
     *
     * @return string
     */
    public function getProgram();

    /**
     * @param string
     * @return self
     */
    public function setProgram($program);
}
