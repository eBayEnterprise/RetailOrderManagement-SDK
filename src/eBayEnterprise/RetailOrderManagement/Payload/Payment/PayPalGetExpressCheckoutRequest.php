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

use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;

class PayPalGetExpressCheckoutRequest implements IPayPalGetExpressCheckoutRequest
{
    use TOrderId;
    use TPayPalCurrencyCode;
    use TPayPalToken;
    use TPayPalValidators;
    use TStrings;

    /** @var array */
    protected $nodesMap = [
        'orderId' => 'string(x:OrderId)',
        'token' => 'string(x:Token)',
        'currencyCode' => 'string(x:CurrencyCode)',
    ];

    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;

    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }
    /**
     * Return the string form of the payload data for transmission.
     * Validation is implied.
     *
     * @throws Exception\InvalidPayload
     * @return string
     */
    public function serialize()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\IPayload
        // Ensure this payload is valid
        $this->validate();

        $xmlString = sprintf(
            '<%s xmlns="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->serializeOrderId() . $this->serializeToken() . $this->serializeCurrencyCode()
        );

        // validate the XML we just created
        $doc = new \DOMDocument();
        $doc->loadXML($xmlString);
        $xml = $doc->C14N();

        $this->schemaValidate($xml);
        return $xml;
    }
    /**
     * Fill out this payload object with data from the supplied string.
     *
     * @throws Exception\InvalidPayload
     * @param string $string
     * @return self
     */
    public function deserialize($serializedPayload)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\IPayload
        $this->schemaValidate($serializedPayload);
        $dom = new \DomDocument();
        $dom->loadXML($serializedPayload);
        $domXPath = new \DOMXPath($dom);
        $domXPath->registerNamespace('x', self::XML_NS);

        foreach ($this->nodesMap as $property => $xPath) {
            $this->$property = $domXPath->evaluate($xPath);
        }

        $this->validate();
        return $this;
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
    }
}
