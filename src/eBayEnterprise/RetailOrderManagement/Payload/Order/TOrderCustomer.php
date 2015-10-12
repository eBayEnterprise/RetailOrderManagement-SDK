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

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPersonName;

trait TOrderCustomer
{
    use TPersonName, TLoyaltyProgramContainer;

    /** @var string */
    protected $customerId;
    /** @var string */
    protected $gender;
    /** @var DateTime */
    protected $dateOfBirth;
    /** @var string */
    protected $emailAddress;
    /** @var string */
    protected $taxId;
    /** @var bool */
    protected $taxExempt;

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $this->cleanString($customerId, 40);
        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setGender($gender)
    {
        $this->gender = $this->cleanString($gender, 1);
        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth(DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $this->cleanString($emailAddress, 150);
        return $this;
    }

    public function getTaxId()
    {
        return $this->taxId;
    }

    public function setTaxId($taxId)
    {
        $this->taxId = $this->cleanString($taxId, 40);
        return $this;
    }

    public function getIsTaxExempt()
    {
        return $this->taxExempt;
    }

    public function setIsTaxExempt($taxExempt)
    {
        $this->taxExempt = (bool) $taxExempt;
        return $this;
    }

    /**
     * Serialize the customer data into a string of XML.
     *
     * @return string
     */
    protected function serializeOrderCustomer()
    {
        return '<Customer ' . $this->serializeOptionalAttribute('customerId', $this->xmlEncode($this->getCustomerId())) . '>'
            . $this->serializePersonName()
            . $this->serializeOptionalValue('Gender', $this->xmlEncode($this->getGender()))
            . $this->serializeOptionalDateValue('DateOfBirth', 'Y-m-d', $this->getDateOfBirth())
            . $this->serializeOptionalXmlEncodedValue('EmailAddress', $this->getEmailAddress())
            . $this->serializeOptionalXmlEncodedValue('CustomerTaxId', $this->getTaxId())
            . $this->serializeTaxExemptFlag()
            . $this->getLoyaltyPrograms()->serialize()
            . '</Customer>';
    }

    /**
     * When the tax exempt flag (isTaxExempt) is set, include an xsd:boolean
     * serialization of the value. Otherwise, return an empty string,
     * no serialization.
     *
     * @return string
     */
    protected function serializeTaxExemptFlag()
    {
        $taxExempt = $this->getIsTaxExempt();
        // Do not include a serialization if a value hasn't been provided.
        return !is_null($taxExempt)
            ? "<TaxExemptFlag>{$this->convertBooleanToString($taxExempt)}</TaxExemptFlag>"
            : '';
    }
}
