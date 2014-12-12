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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

use DOMDocument;
use eBayEnterprise\RetailOrderManagement\Util\TTestReflection;

class PhysicalAddressTest extends \PHPUnit_Framework_TestCase
{
    use TTestReflection;

    /** @var TPhysicaAddress (mock) */
    protected $testTrait;

    public function setUp()
    {
        $this->testTrait = $this
            ->getMockForTrait('\eBayEnterprise\RetailOrderManagement\Payload\OrderEvents\TPhysicalAddress');
        $this->testTrait->expects($this->any())
            ->method('getPhysicalAddressRootNodeName')
            ->will($this->returnValue('Address'));
        $this->testTrait->expects($this->any())
            ->method('cleanString')
            ->will($this->returnArgument(0));
    }

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

    /**
     * Provide address data for the trait and the XML file containing the
     * expected XML for that set of data.
     *
     * @return array
     */
    public function provideAddressDataAndFixtureFile()
    {
        return [
            [
                "123 Main St\nSte 2\nBldg 6",
                'King of Prussia',
                'PA',
                'US',
                '19406',
                'PhysicalAddress.xml'
            ],
            [
                "123 Main St\nSte 2\nBldg 6\nLast\nConcat\nIn\nLast",
                'King of Prussia',
                null,
                'US',
                null,
                'PhysicalAddressOverFourLinesNoOpt.xml'
            ],
        ];
    }

    /**
     * Test serializing physical address data.
     *
     * @param string
     * @param string
     * @param string|null
     * @param string
     * @param string|null
     * @param string Name of file in Fixtures directory to load as expeted XML
     * @dataProvider provideAddressDataAndFixtureFile
     */
    public function testSerializePhysicalAddress($lines, $city, $mainDiv, $countryCode, $postCode, $fixtureFile)
    {
        $this->testTrait->setLines($lines)
            ->setCity($city)
            ->setMainDivision($mainDiv)
            ->setCountryCode($countryCode)
            ->setPostalCode($postCode);

        $this->assertSame(
            $this->loadXmlTestString(__DIR__ . "/Fixtures/$fixtureFile", true),
            $this->invokeRestrictedMethod($this->testTrait, 'serializePhysicalAddress')
        );
    }

    /**
     * When a physical address has no lines, should not return any serialized data.
     */
    public function testSerializePhysicalAddressNoLines()
    {
        $this->testTrait->setLines('')
            ->setCity('King of Prussia')
            ->setMainDivision('PA')
            ->setCountryCode('US')
            ->setPostalCode('19406');

        $this->assertSame('', $this->invokeRestrictedMethod($this->testTrait, 'serializePhysicalAddress'));
    }
}
