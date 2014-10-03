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

class XsdSchemaValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * XML not matching the schema should result in an exception.
     */
    public function testValidateInvalidXml()
    {
        $this->setExpectedException(
            'eBayEnterprise\RetailOrderManagement\Payload\Exception\InvalidPayload',
            'XSD validation failed with following messages:'
        );
        $validator = new XsdSchemaValidator();
        $validator->validate(file_get_contents(__DIR__ . '/Fixtures/InvalidXml.xml'), __DIR__ . '/Fixtures/TestSchema.xsd');
    }
    /**
     * XML matching the schema should simply return self.
     */
    public function testValidateValidXml()
    {
        $validator = new XsdSchemaValidator();
        $this->assertSame(
            $validator,
            $validator->validate(file_get_contents(__DIR__ . '/Fixtures/ValidXml.xml'), __DIR__ . '/Fixtures/TestSchema.xsd')
        );
    }
    /**
     * Test building the error message from the libxml errors. Should return a
     * string with each libXMLError formatted.
     */
    public function testBuildErrorMessage()
    {
        $warnError = new \libXMLError();
        $warnError->level = LIBXML_ERR_WARNING;
        $warnError->message = 'Warning message';
        $warnError->file = 'some/file/path.xml';
        $warnError->line = 22;

        $errError = new \libXMLError();
        $errError->level = LIBXML_ERR_ERROR;
        $errError->message = 'Error message';
        $errError->file = 'some/file/path.xml';
        $errError->line = 23;

        $fatalError = new \libXMLError();
        $fatalError->level = LIBXML_ERR_FATAL;
        $fatalError->message = 'Fatal message';
        $fatalError->file = 'some/file/path.xml';
        $fatalError->line = 25;

        $errors = array($warnError, $errError, $fatalError);
        $validator = new XsdSchemaValidator();
        $method = new \ReflectionMethod($validator, 'formatErrors');
        $method->setAccessible(true);
        $this->assertSame(
            "XSD validation failed with following messages:\n[some/file/path.xml:22] Warning message\n[some/file/path.xml:23] Error message\n[some/file/path.xml:25] Fatal message",
            $method->invoke($validator, $errors)
        );
    }
}
