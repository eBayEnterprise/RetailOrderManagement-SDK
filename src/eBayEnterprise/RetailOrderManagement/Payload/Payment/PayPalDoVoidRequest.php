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

class PayPalDoVoidRequest implements IPayPalDoVoidRequest
{
    use TOrderId;
    use TPayPalCurrencyCode;
    use TPayPalValidators;
    use TStrings;

    /** @var string **/
    protected $requestId;
    /** @var array string **/
    protected $nodesMap = [
        'requestId' => ' string(@requestId)',
        'currencyCode' => 'string(x:CurrencyCode)',
        'orderId' => 'string(x:OrderId)',
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
     * RequestId is used to globally identify a request message and is used
     * for duplicate request protection.
     *
     * xsd restrictions: 1-40 characters
     * @return string
     */
    public function getRequestId()
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalDoVoidRequest
        return $this->requestId;
    }
    /**
     * @param string
     * @return self
     */
    public function setRequestId($requestId)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\Payment\IPayPalDoVoidRequest
        $this->requestId = $requestId;
        return $this;
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
        // validate payload
        $this->validate();

        $xmlString = sprintf(
            '<%s xmlns="%s" requestId="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->getRequestId(),
            $this->serializeOrderId() . $this->serializeCurrencyCode()
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
    public function deserialize($string)
    {
        // As from eBayEnterprise\RetailOrderManagement\Payload\IPayload
        $this->schemaValidate($string);
        $dom = new \DOMDocument();
        $dom->loadXML($string);

        $domXPath = new \DOMXPath($dom);
        $domXPath->registerNamespace('x', self::XML_NS);

        foreach ($this->nodesMap as $property => $xPath) {
            $this->$property = $domXPath->evaluate($xPath);
        }

        // validate ourself, throws Exception\InvalidPayload if we don't pass
        $this->validate();

        return $this;
    }
}
