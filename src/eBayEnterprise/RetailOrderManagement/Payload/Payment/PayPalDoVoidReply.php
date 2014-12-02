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
use eBayEnterprise\RetailOrderManagement\Payload\Exception;

class PayPalDoVoidReply implements IPayPalDoVoidReply
{
    const SUCCESS = 'Success';

    use Payload\TPayload, TOrderId;

    /** @var string */
    protected $responseCode;

    public function __construct(Payload\IValidatorIterator $validators, Payload\ISchemaValidator $schemaValidator)
    {
        $this->extractionPaths = [
            'responseCode' => 'string(x:ResponseCode)',
            'orderId' => 'string(x:OrderId)',
        ];
        $this->validators = $validators;
        $this->schemaValidator = $schemaValidator;
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
     * The XML namespace for the payload.
     *
     * @return string
     */
    protected function getXmlNamespace()
    {
        return static::XML_NS;
    }

    /**
     * Serialize the various parts of the payload into XML strings and concatenate them together.
     *
     * @return string
     */
    protected function serializeContents()
    {
        return $this->serializeOrderId() . $this->serializeResponseCode();
    }

    /**
     * Get the response code as xml.
     *
     * @return string
     */
    protected function serializeResponseCode()
    {
        return "<ResponseCode>{$this->getResponseCode()}</ResponseCode>";
    }
}
