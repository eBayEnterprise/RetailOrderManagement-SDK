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
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;

class PayPalDoVoidRequest implements IPayPalDoVoidRequest
{
    use TPayload, TOrderId, TPayPalCurrencyCode;

    /** @var string * */
    protected $requestId;

    /**
     * @param IValidatorIterator $validators
     * @param ISchemaValidator $schemaValidator
     */
    public function __construct(IValidatorIterator $validators, ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'requestId' => ' string(@requestId)',
            'currencyCode' => 'string(x:CurrencyCode)',
            'orderId' => 'string(x:OrderId)',
        ];
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
    protected function serializeContents()
    {
        return $this->serializeOrderId() . $this->serializeCurrencyCode();
    }

    /**
     * Return the schema file path.
     * @return string
     */
    protected function getSchemaFile()
    {
        return __DIR__ . '/schema/' . self::XSD;
    }

    /**
     * Return the name of the xml root node.
     *
     * @return string
     */
    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    /**
     * Name, value pairs of root attributes
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
            'requestId' => $this->getRequestId(),
        ];
    }

    /**
     * The XML namespace for the payload.
     *
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
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
}
