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

namespace eBayEnterprise\RetailOrderManagement\Payload\Address;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPhysicalAddress;

trait TResultAddress
{
    use TPhysicalAddress, TErrorLocationContainer;

    /** @var string */
    protected $formattedAddress;

    public function getFormattedAddress()
    {
        return $this->formattedAddress;
    }

    public function setFormattedAddress($formattedAddress)
    {
        $this->formattedAddress = $formattedAddress;
        return $this;
    }

    protected function buildPhysicalAddressNodes(array $lines)
    {
        return sprintf(
            '<%s>%s<City>%s</City>%s<CountryCode>%s</CountryCode>%s%s%s</%1$s>',
            $this->getPhysicalAddressRootNodeName(),
            implode('', $lines),
            $this->xmlEncode($this->getCity()),
            $this->serializeOptionalXmlEncodedValue('MainDivision', $this->getMainDivision()),
            $this->xmlEncode($this->getCountryCode()),
            $this->serializeOptionalXmlEncodedValue('PostalCode', $this->getPostalCode()),
            $this->serializeOptionalXmlEncodedValue('FormattedAddress', $this->getFormattedAddress()),
            $this->getErrorLocations()->serialize()
        );
    }

    /**
     * Serialize an optional element containing a string. The value will be
     * xml-encoded if is not null.
     *
     * @param string
     * @param string
     * @return string
     */
    abstract protected function serializeOptionalXmlEncodedValue($name, $value);

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
