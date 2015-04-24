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

interface ITaxDutyFeeQuoteRequest extends ITaxDutyFeeQuote, IShipGroupContainer, IDestinationContainer
{
    const XSD = '/checkout/1.0/TaxDutyFee-QuoteRequest-1.0.xsd';

    /**
     * Currency code for the request.
     *
     * Must conform to ISO 4217:2008
     * @link http://en.wikipedia.org/wiki/ISO_4217
     *
     * restrictions: 2 >= length <= 40
     * @return string
     */
    public function getCurrency();

    /**
     * @param string
     * @return self
     */
    public function setCurrency($currency);

    /**
     * Flag indicating prices already have VAT tax included.
     *
     * restrictions: optional
     * @return bool
     */
    public function getVatInclusivePricingFlag();

    /**
     * @param bool
     * @return self
     */
    public function setVatInclusivePricingFlag($flag);

    /**
     * Tax Identifier for the customer.
     *
     * restrictions: optional
     * @return string
     */
    public function getCustomerTaxId();

    /**
     * @param string
     * @return self
     */
    public function setCustomerTaxId($id);

    /**
     * Customer billing address
     *
     * @return IDestination
     */
    public function getBillingInformation();

    /**
     * @param IDestination
     * @return self
     */
    public function setBillingInformation(IDestination $billingDest);
}
