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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

/**
 * Interface IBillingAddress
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IBillingAddress
{
    /**
     * The street address and/or suite and building
     *
     * Newline-delimited string, at most four lines
     * xsd restriction: 1-70 characters per line
     * @return string
     */
    public function getBillingLines();

    /**
     * @param string $lines
     * @return self
     */
    public function setBillingLines($lines);

    /**
     * Name of the city
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    public function getBillingCity();

    /**
     * @param string $city
     * @return self
     */
    public function setBillingCity($city);

    /**
     * Typically a two- or three-digit postal abbreviation for the state or province.
     * ISO 3166-2 code is recommended, but not required
     *
     * xsd restriction: 1-35 characters
     * @return string
     */
    public function getBillingMainDivision();

    /**
     * @param string $div
     * @return self
     */
    public function setBillingMainDivision($div);

    /**
     * Two character country code.
     *
     * xsd restriction: 2-40 characters
     * @return string
     */
    public function getBillingCountryCode();

    /**
     * @param string $code
     * @return self
     */
    public function setBillingCountryCode($code);

    /**
     * Typically, the string of letters and/or numbers that more closely
     * specifies the delivery area than just the City component alone,
     * for example, the Zip Code in the U.S.
     *
     * xsd restriction: 1-15 characters
     * @return string
     */
    public function getBillingPostalCode();

    /**
     * @param string $code
     * @return self
     */
    public function setBillingPostalCode($code);
}
