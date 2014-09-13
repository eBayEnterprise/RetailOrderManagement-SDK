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
 * Interface ITax
 * @package eBayEnterprise\RetailOrderManagement\Payload\Checkout
 */
interface ITax extends ISerializable
{
    /**
     * The situs or taxing location as determined by the calculation engine for the line item.
     *
     * @return string
     */
    function getSitus();
    /**
     * The name of jurisdiction to which a tax is applied.
     *
     * @return IJurisdiction
     */
    function getJurisdiction();
    /**
     * The name of the imposition to which the relevant tax rule belongs.
     *
     * @return IImposition
     */
    function getImposition();
    /**
     * For Buyer Input tax and Seller Import tax, this rate is calculated based on the Extended Price
     * and Tax Amount (Import or Input) passed in the Request message.
     * If you total the Extended Price and Tax Amounts before passing them in, this rate is an average.
     * For all other message types, this is the effective rate the system used to calculate tax.
     *
     * @return float
     */
    function getEffectiveRate();
    /**
     * The amount for which tax is calculated.
     *
     * @return IAmountBase
     */
    function getTaxableAmount();
    /**
     * Amount of the line item not subject to tax due to exempt status.
     *
     * @return IAmountBase
     */
    function getExemptAmount();
    /**
     * Amount of the line item not subject to tax due to nontaxable status.
     *
     * @return IAmountBase
     */
    function getNonTaxableAmount();
    /**
     * Amount of tax calculated by the calculation engine.
     *
     * @return IAmountBase
     */
    function getCalculatedTax();
    /**
     * The Registration ID for the Seller associated with this line item tax.
     *
     * @return string
     */
    function getSellerRegistrationId();
    /**
     * @return IInvoiceTextCode[]
     */
    function getInvoiceTextCodes();
    /**
     * @return ITaxType
     */
    function getTaxType();
    /**
     * @return ITaxability
     */
    function getTaxability();
}
