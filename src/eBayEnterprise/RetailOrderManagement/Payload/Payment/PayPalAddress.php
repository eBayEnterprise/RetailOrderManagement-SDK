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

use eBayEnterprise\RetailOrderManagement\Payload;

class PayPalAddress implements IPayPalAddress
{
    use TPhysicalAddress;
    use TPayPalValidators;

    /** @var string **/
    protected $addressStatus;

    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemavalidator = $schemaValidator;
    }

    /**
     * Status of street address on file with PayPal.
     * It is one of the following values: "none", "Confirmed", "Unconfirmed"
     *
     * @return string
     */
    public function getAddressStatus()
    {
        return $this->addressStatus;
    }
    /**
     * @param string
     * @return self
     */
    public function setAddressStatus($status)
    {
        $this->addressStatus = $status;
        return $this;
    }
    public function serialize()
    {
        $this->validate();
        return $this->serializeAddressFields() . $this->serializeAddressStatus();
    }
    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $string
     * @return self
     */
    public function deserialize($string)
    {
        $xpath = $this->getPayloadAsXPath($string);
        $this->deserializePhysicalAddressFields($xpath); // see TPhysicalAddress

        $foundNode = $xpath->query('AddressStatus')->item(0);
        $this->addressStatus = $foundNode ? $foundNode->nodeValue : null;

        // payload is only valid of the unserialized data is also valid
        $this->validate();
        return $this;
    }
    /**
     * schema validation is a no-op for this payload
     * @return self
     */
    public function schemaValidate()
    {
        return $this;
    }
    /**
     * serialize the billing address status.
     * @return string
     */
    protected function serializeAddressStatus()
    {
        return $this->getAddressStatus() ?
            "<AddressStatus>{$this->getAddressStatus()}</AddressStatus>" :
            '';
    }
    /**
     * Load the payload XML into a DOMDocument.
     * @param string $xmlString
     * @return DOMXPath
     */
    protected function getPayloadAsDoc($xmlString)
    {
        $d = new \DOMDocument();
        $d->loadXML($xmlString);
        return $d;
    }
    /**
     * Load the payload XML into a DOMXPath for querying.
     * @param string $xmlString
     * @return DOMXPath
     */
    protected function getPayloadAsXPath($xmlString)
    {
        $xpath = new \DOMXPath($this->getPayloadAsDoc($xmlString));
        return $xpath;
    }
}
