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
     * Return the name of the fixture file containing a complte data set to be
     * used for base serialize/deserialize tests.
     *
     * @return string
     */
    abstract protected function getCompleteFixtureFile();

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

    public function testSerialize()
    {
        $this->assertSame(
            $this->loadXmlTestString($this->getCompleteFixtureFile(), true),
            $this->fullPayload->serialize()
        );
    }

    public function testDeserialize()
    {
        $payload = $this->buildPayload();
        $this->assertEquals(
            $this->fullPayload,
            $payload->deserialize($this->loadXmlTestString($this->getCompleteFixtureFile()))
        );
    }

    /**
     * Asserts that two variables have the same type and value.
     * Used on objects, it asserts that two variables reference
     * the same object.
     *
     * @param mixed  $expected
     * @param mixed  $actual
     * @param string $message
     */
    abstract public static function assertSame($expected, $actual, $message = '');
    /**
     * Asserts that two variables are equal.
     *
     * @param mixed   $expected
     * @param mixed   $actual
     * @param string  $message
     * @param float   $delta
     * @param integer $maxDepth
     * @param boolean $canonicalize
     * @param boolean $ignoreCase
     */
    abstract public static function assertEquals(
        $expected,
        $actual,
        $message = '',
        $delta = 0.0,
        $maxDepth = 10,
        $canonicalize = false,
        $ignoreCase = false
    );
}
