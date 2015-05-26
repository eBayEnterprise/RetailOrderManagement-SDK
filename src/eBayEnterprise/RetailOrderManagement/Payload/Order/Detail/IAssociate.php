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

interface IAssociate extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'Associate';

    /**
     * Allowable Values: Text string Name of the sales person.
     * Required: Yes
     * Length: TBD
     * Default Value: blank
     *
     * @return string
     */
    public function getName();

    /**
     * @param  string
     * @return self
     */
    public function setName($name);

    /**
     * Store-assigned ID number of the sales person.
     * Allowable Values: Text string
     * Required: Yes
     * Length: TBD
     * Default Value: blank
     *
     * @return string
     */
    public function getNumber();

    /**
     * @param  string
     * @return self
     */
    public function setNumber($number);

    /**
     * A unique identifier for the store.
     * Allowable Values: Text string
     * Required: Yes
     * Length: TBD
     * Default Value: blank
     *
     * @return string
     */
    public function getStore();

    /**
     * @param  string
     * @return self
     */
    public function setStore($store);
}
