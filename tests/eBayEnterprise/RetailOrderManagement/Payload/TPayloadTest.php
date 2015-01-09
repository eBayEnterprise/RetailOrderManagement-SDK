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

namespace eBayEnterprise\RetailOrderManagement\Payload;

use DOMDocument;

trait TPayloadTest
{
    /** @var Payload\IValidator (stub) */
    protected $stubValidator;
    /** @var Payload\IValidatorIterator */
    protected $validatorIterator;
    /** @var Payload\ISchemaValidator (stub) */
    protected $stubSchemaValidator;
    /** @var IPayload A sample payload with data matching the serialized fixture data */
    protected $fullPayload;
    /** @var PayloadFactory */
    protected $payloadFactory;

    /**
     * Create a new payload and set any data passed in the properties param.
     * Each key in array should be a setter method to call and will be given
     * the value at that key.
     *
     * @param  array
     * @return IPayload
     */
    protected function buildPayload(array $properties = [])
    {
        $payload = $this->createNewPayload();

        foreach ($properties as $setterMethod => $value) {
            $payload->$setterMethod($value);
        }
        return $payload;
    }

    /**
     * Construct a new IPayload object.
     *
     * @return IPayload
     */
    abstract protected function createNewPayload();

    /**
     * Return a C14N, whitespace removed, XML string. If $removeNs is true, any
     * xmlns values will be removed from the XML - allows same file to be used
     * for serialize expectation (no xmlns) and serialize provider (needs xmlns).
     *
     * @param string Path to xml file
     * @param bool
     */
    protected function loadXmlTestString($fixtureFile, $removeNs = false)
    {
        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        $xmlString = file_get_contents($fixtureFile);
        if ($removeNs) {
            $xmlString = preg_replace('#xmlns="[^"]*"#', '', $xmlString);
        }
        $dom->loadXML($xmlString);
        $string = $dom->C14N();

        return $string;
    }
}
