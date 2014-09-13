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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;
use eBayEnterprise\RetailOrderManagement\Payload\ISerializable;

/**
 * The street, city, state and country of a location.
 *
 * Interface IPhysicalAddress
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface IPhysicalAddress extends ISerializable
{
    /**
     * The "Line#" components contain the street address and,
     * if necessary, suite and building identifiers for the physical address.
     *
     * @return IAddressLine
     */
    function getLine1();
    function getLine2();
    function getLine3();
    function getLine4();
    /**
     * Name of the city
     *
     * @return string
     */
    function getCity();
    /**
     * Typically a two- or three-digit postal abbreviation for the state or province
     * Use of the ISO 3166-2 code is recommended
     *
     * @return string
     */
    function getMainDivision();
    /**
     * Two digit country code.
     * ISO 3166 alpha 2 code is recommended
     *
     * @return string
     */
    function getCountryCode();
    /**
     * Typically, the string of letters and/or numbers that more closely specifies the delivery area than just
     * the City component alone, for example, the Zip Code in the U.S.
     *
     * @return string
     */
    function getPostalCode();
}
