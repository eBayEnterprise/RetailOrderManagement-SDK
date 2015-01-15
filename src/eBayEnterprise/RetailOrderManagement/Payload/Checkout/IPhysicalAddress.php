<?php
/**
 * Copyright (c) 2013-2015 eBay Enterprise, Inc.
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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

interface IPhysicalAddress
{
    /**
     * The street address and/or suite and building
     *
     * Newline-delimited string, at most four lines
     * restriction: 1-70 characters per line
     * @return string
     */
    public function getLines();

    /**
     * @param string
     * @return self
     */
    public function setLines($lines);

    /**
     * Name of the city
     *
     * restriction: 1-35 characters
     * @return string
     */
    public function getCity();

    /**
     * @param string
     * @return self
     */
    public function setCity($city);

    /**
     * Typically a two- or three-digit postal abbreviation for the state or province.
     * ISO 3166-2 code is recommended, but not required
     *
     * restriction: 1-35 characters
     * @return string
     */
    public function getMainDivision();

    /**
     * @param string
     * @return self
     */
    public function setMainDivision($div);

    /**
     * Two character country code.
     *
     * restriction: 2-40 characters
     * @return string
     */
    public function getCountryCode();

    /**
     * @param string
     * @return self
     */
    public function setCountryCode($code);

    /**
     * Typically, the string of letters and/or numbers that more closely
     * specifies the delivery area than just the City component alone,
     * for example, the Zip Code in the U.S.
     *
     * restriction: 1-15 characters
     * @return string
     */
    public function getPostalCode();

    /**
     * @param string
     * @return self
     */
    public function setPostalCode($code);
}
