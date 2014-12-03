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

namespace eBayEnterprise\RetailOrderManagement\Payload\Validator;

use DOMDocument;
use eBayEnterprise\RetailOrderManagement\Payload;

/**
 * Class XsdSchemaValidator
 * @package eBayEnterprise\RetailOrderManagement\Payload\Validator
 */
class XsdSchemaValidator implements Payload\ISchemaValidator
{
    /**
     * Validate the serialized string as XML against a provided XSD schema.
     * @param  string $xmlString
     * @param  string $schemaFile Path to XSD file
     * @return self
     * @throws Payload\Exception\InvalidPayload If the XML does not pass XSD schema validation.
     */
    public function validate($xmlString, $schemaFile = null)
    {
        $doc = $this->loadXmlDoc($xmlString);
        $originalUseErrors = $this->setupValidationErrorHandling();
        $isValid = $doc->schemaValidate($schemaFile);
        $errors = $this->retrieveErrors();
        $this->teardownValidationErrorHandling($originalUseErrors);
        if (!$isValid) {
            throw new Payload\Exception\InvalidPayload($this->formatErrors($errors));
        }
        return $this;
    }

    /**
     * Load the XML string into a DOMDocument
     * @param  string $xmlString
     * @return \DOMDocument
     */
    protected function loadXmlDoc($xmlString)
    {
        $d = new DOMDocument();
        $d->loadXML($xmlString);
        return $d;
    }

    /**
     * Set libxml to use user defined error handling. Return the previous
     * setting to be reset after schema validation is complete.
     * @return bool
     */
    protected function setupValidationErrorHandling()
    {
        return libxml_use_internal_errors(true);
    }

    /**
     * Retrieve errors encountered while validating the XML against the schema.
     * @return array
     */
    protected function retrieveErrors()
    {
        return libxml_get_errors();
    }

    /**
     * Reset libxml error handling.
     * @param  bool $oldUseErrors Value to reset
     * @return self
     */
    protected function teardownValidationErrorHandling($oldUseErrors)
    {
        libxml_use_internal_errors($oldUseErrors);
        return $this;
    }

    /**
     * Format the array of validation errors.
     * @param  \libXMLError[] $libxmlErrors
     * @return string
     */
    protected function formatErrors(array $libxmlErrors = [])
    {
        return 'XSD validation failed with following messages:' . PHP_EOL . implode('', array_map(
            function (\libXMLError $xmlError) {
                return sprintf(
                    '[%s:%d] %s',
                    $xmlError->file,
                    $xmlError->line,
                    $xmlError->message
                );
            },
            $libxmlErrors
        ));
    }
}
