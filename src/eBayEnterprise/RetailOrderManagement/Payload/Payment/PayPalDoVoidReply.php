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

class PayPalDoVoidReply implements IPayPalDoVoidReply
{
    const SUCCESS = 'Success';

    use TOrderId;
    use TPayPalValidators;
    use TStrings;

    /** @var string **/
    protected $responseCode;
    /** @var array **/
    protected $nodesMap = [
        'responseCode' => 'string(x:ResponseCode)',
        'orderId' => 'string(x:OrderId)',
    ];

    /** @var IValidatorIterator */
    protected $validators;
    /** @var ISchemaValidator */
    protected $schemaValidator;

    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
    }

    /**
     * Response code like Success, Failure etc
     *
     * @return string
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
    /**
     * @param string
     * @return self
     */
    public function setResponseCode($code)
    {
        $this->responseCode = $code;
        return $this;
    }
    /**
     * Should downstream systems consider this reply a success?
     *
     * @return bool
     */
    public function isSuccess()
    {
        return ($this->getResponseCode() === static::SUCCESS);
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
        // validate payload
        $this->validate();

        $xmlString = sprintf(
            '<%s xmlns="%s">%s</%1$s>',
            self::ROOT_NODE,
            self::XML_NS,
            $this->serializeOrderId() . "<ResponseCode>{$this->getResponseCode()}</ResponseCode>"
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

