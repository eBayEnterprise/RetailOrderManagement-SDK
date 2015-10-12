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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

trait TTaxContainer
{
    use TTaxClass;

    /** @var ITaxIterable */
    protected $taxes;

    public function getTaxes()
    {
        return $this->taxes;
    }

    /**
     * @param ITaxIterable
     * @return self
     */
    public function setTaxes(ITaxIterable $taxes)
    {
        $this->taxes = $taxes;
        return $this;
    }

    /**
     * Serialize the tax class and taxes in the container.
     *
     * @return string
     */
    protected function serializeTaxData()
    {
        $taxClass = $this->getTaxClass();
        $taxes = $this->getTaxes();
        return (!is_null($taxClass) || count($taxes))
            ? '<TaxData>'
                . $this->serializeOptionalXmlEncodedValue('TaxClass', $this->getTaxClass())
                . $this->getTaxes()->serialize()
                . '</TaxData>'
            : '';
    }
}
