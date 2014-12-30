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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

trait TCustomer
{
    /** @var string */
    protected $customerId;
    /** @var string */
    protected $customerFirstName;
    /** @var string */
    protected $customerLastName;
    /** @var string */
    protected $customerMiddleName;
    /** @var string */
    protected $customerHonorificName;
    /** @var string */
    protected $customerEmailAddress;

    public function getCustomerId()
    {
        return $this->customerId;
    }

    public function setCustomerId($customerId)
    {
        $this->customerId = $this->cleanString($customerId, 40) ?: null;
        return $this;
    }

    public function getCustomerFirstName()
    {
        return $this->customerFirstName;
    }

    public function setCustomerFirstName($customerFirstName)
    {
        $this->customerFirstName = $this->cleanString($customerFirstName, 64);
        return $this;
    }

    public function getCustomerLastName()
    {
        return $this->customerLastName;
    }

    public function setCustomerLastName($customerLastName)
    {
        $this->customerLastName = $this->cleanString($customerLastName, 64);
        return $this;
    }

    public function getCustomerMiddleName()
    {
        return $this->customerMiddleName;
    }

    public function setCustomerMiddleName($customerMiddleName)
    {
        $this->customerMiddleName = $this->cleanString($customerMiddleName, 40);
        return $this;
    }

    public function getCustomerHonorificName()
    {
        return $this->customerHonorificName;
    }

    public function setCustomerHonorificName($customerHonorificName)
    {
        $this->customerHonorificName = $this->cleanString($customerHonorificName, 10);
        return $this;
    }

    public function getCustomerEmailAddress()
    {
        return $this->customerEmailAddress;
    }

    public function setCustomerEmailAddress($customerEmailAddress)
    {
        $this->customerEmailAddress = $this->cleanString($customerEmailAddress, 150);
        return $this;
    }

    protected function serializeCustomer()
    {
        $customerId = $this->getCustomerId();
        return sprintf(
            '<Customer %s>%s%s</Customer>',
            $customerId ? "customerId='$customerId'" : '',
            $this->serializeCustomerName(),
            $this->serializeCustomerEmail()
        );
    }

    protected function serializeCustomerName()
    {
        $honorific = $this->getCustomerHonorificName();
        $middleName = $this->getCustomerMiddleName();
        return sprintf(
            '<Name>%s<LastName>%s</LastName>%s<FirstName>%s</FirstName></Name>',
            ($honorific ? "<Honorific>$honorific</Honorific>" : ''),
            $this->getCustomerLastName(),
            ($middleName ? "<MiddleName>$middleName</MiddleName>" : ''),
            $this->getCustomerFirstName()
        );
    }

    protected function serializeCustomerEmail()
    {
        $email = $this->getCustomerEmailAddress();
        return $email
            ? "<EmailAddress>{$this->getCustomerEmailAddress()}</EmailAddress>"
            : '';
    }
}
