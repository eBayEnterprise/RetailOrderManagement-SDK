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

interface ITax extends IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const TAX_TYPE_SALES = 'SALES';
    const TAX_TYPE_SELLER_USE = 'SELLER_USE';
    const TAX_TYPE_CONSUMER_USE = 'CONSUMER_USE';
    const TAX_TYPE_VAT = 'VAT';
    const TAX_TYPE_IMPORT_VAT = 'IMPORT_VAT';
    const TAX_TYPE_NONE = 'NONE';
    const TAXABILITY_TAXABLE = 'TAXABLE';
    const TAXABILITY_NONTAXABLE = 'NONTAXABLE';
    const TAXABILITY_EXEMPT = 'EXEMPT';
    const TAXABILITY_DPPAPPLIED = 'DPPAPPLIED';
    const TAXABILITY_NO_TAX = 'NO_TAX';
    const TAXABILITY_DEFERRED = 'DEFERRED';
    const SITUS_ADMINISTRATIVE_DESTINATION = 'ADMINISTRATIVE_DESTINATION';
    const SITUS_ADMINISTRATIVE_ORIGIN = 'ADMINISTRATIVE_ORIGIN';
    const SITUS_DESTINATION = 'DESTINATION';
    const SITUS_PHYSICAL_ORIGIN = 'PHYSICAL_ORIGIN';
    const JURISDICTION_LEVEL_APO = 'APO';
    const JURISDICTION_LEVEL_BOROUGH = 'BOROUGH';
    const JURISDICTION_LEVEL_CITY = 'CITY';
    const JURISDICTION_LEVEL_COUNTRY = 'COUNTRY';
    const JURISDICTION_LEVEL_COUNTY = 'COUNTY';
    const JURISDICTION_LEVEL_DISTRICT = 'DISTRICT';
    const JURISDICTION_LEVEL_FPO = 'FPO';
    const JURISDICTION_LEVEL_LOCAL_IMPROVEMENT_DISTRICT = 'LOCAL_IMPROVEMENT_DISTRICT';
    const JURISDICTION_LEVEL_PARISH = 'PARISH';
    const JURISDICTION_LEVEL_SPECIAL_PURPOSE_DISTRICT = 'SPECIAL_PURPOSE_DISTRICT';
    const JURISDICTION_LEVEL_STATE = 'STATE';
    const JURISDICTION_LEVEL_TERRITORY = 'TERRITORY';
    const JURISDICTION_LEVEL_TOWNSHIP = 'TOWNSHIP';
    const JURISDICTION_LEVEL_TRADE_BLOCK = 'TRADE_BLOCK';
    const JURISDICTION_LEVEL_TRANSIT_DISTRICT = 'TRANSIT_DISTRICT';
    const JURISDICTION_LEVEL_PROVINCE = 'PROVINCE';

    /**
     * Type of tax indicated by tax service. Expected to be one of:
     * - SALES
     * - SELLER_USE
     * - CONSUMER_USE
     * - VAT
     * - IMPORT_VAT
     *
     * @return string
     */
    public function getType();

    /**
     * @param string
     * @return self
     */
    public function setType($type);

    /**
     * Taxable status of the item taxes were calculated for.
     *
     * restrictions: optional, one of "TAXABLE", "NONTAXABLE", "EXEMPT", "DPPAPPLIED", "NO_TAX", "DEFERRED"
     * @return string
     */
    public function getTaxability();

    /**
     * @param string
     * @return self
     */
    public function setTaxability($taxability);

    /**
     * Type of location the tax calculation is based on. Expected to be one
     * of:
     * - ADMINISTRATIVE_DESTINATION
     * - ADMINISTRATIVE_ORIGIN
     * - DESTINATION
     * - PHYSICAL_ORIGIN
     *
     * @return string
     */
    public function getSitus();

    /**
     * @param string
     * @return self
     */
    public function setSitus($situs);

    /**
     * Name of the jurisdiction tax calculation is for.
     *
     * restrictions: optional
     * @return string
     */
    public function getJurisdiction();

    /**
     * @param string
     * @return self
     */
    public function setJurisdiction($jurisdiction);

    /**
     * Jurisdiction's classification based on its geopolitical and/or taxing context.
     * Allowed values: APO, BOROUGH, CITY, COUNTRY, COUNTY, DISTRICT,
     * FPO LOCAL_IMPROVEMENT_DISTRICT, PARISH, PROVINCE, SPECIAL_PURPOSE_DISTRICT,
     * STATE, TERRITORY, TOWNSHIP, TRADE_BLOCK, TRANSIT_DISTRICT
     *
     * restrictions: optional
     * @return string
     */
    public function getJurisdictionLevel();

    /**
     * @param string
     * @return self
     */
    public function setJurisdictionLevel($jurisdictionLevel);

    /**
     * Jurisdiction code assigned by relevant governmental authority.
     *
     * restrictions: optional
     * @return string
     */
    public function getJurisdictionId();

    /**
     * @param string
     * @return self
     */
    public function setJurisdictionId($jurisdictionId);

    /**
     * Category of tax imposed on the item.
     *
     * restrictions: optional
     * @return string
     */
    public function getImposition();

    /**
     * @param string
     * @return self
     */
    public function setImposition($imposition);

    /**
     * Categorizes the tax being imposed.
     *
     * restrictions: optional, string with length <= 60
     * @return string
     */
    public function getImpositionType();

    /**
     * @param string
     * @return self
     */
    public function setImpositionType($impositionType);

    /**
     * Percentage applied to the TaxableAmount to create the CalculatedTax.
     *
     * restrictions: float with 2 decimal precision
     * @return float
     */
    public function getEffectiveRate();

    /**
     * @param float
     * @return self
     */
    public function setEffectiveRate($effectiveRate);

    /**
     * Portion of the order subject to this tax.
     *
     * restrictions: optional, 2 decimal, non-negative
     * @return float
     */
    public function getTaxableAmount();

    /**
     * @param float
     * @return self
     */
    public function setTaxableAmount($taxableAmount);

    /**
     * Amount of tax due.
     *
     * restrictions: 2 decimal, non-negative
     * @return float
     */
    public function getCalculatedTax();

    /**
     * @param float
     * @return self
     */
    public function setCalculatedTax($calculatedTax);

    /**
     * Unique string associated with the entity selling the product.
     *
     * restrictions: optional
     * @return string
     */
    public function getSellerRegistrationId();

    /**
     * @param string
     * @return self
     */
    public function setSellerRegistrationId($sellerRegistrationId);
}
