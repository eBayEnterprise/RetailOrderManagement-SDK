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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\Order\TCustomerSessionInfo;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Validator\XsdSchemaValidator;
use DateInterval;
use DateTime;

class CustomerSessionInfoTest extends \PHPUnit_Framework_TestCase
{
    use TCustomerSessionInfo, TPayload;

    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    public function setUp()
    {
        $this->schemaValidator = new XsdSchemaValidator();
        $this->setTimeSpentOnSite(new DateInterval('P2Y4DT6H8M'))
            ->setLastLogin(new DateTime('2015-06-08 15:05:59'))
            ->setUserPassword('ab3c3w+234!')
            ->setTimeOnFile('600000')
            ->setRtcTransactionResponseCode('Y')
            ->setRtcReasonCode('NONE')
            ->setAuthorizationAttempts(1);
    }

    /**
     * Test that when we serialize the sub-payload <SessionInfo>...</SessionInfo>
     * the node <TimeSpentOnSite> will have the proper value matching the required
     * regular expression pattern.
     */
    public function testSerializeCustomerSessionInfo()
    {
        /** @var string */
        $payload = $this->serializeContents();
        // Testing that TimeSpentOnSite node value match the right regular expression pattern.
        $xpath = $this->getPayloadAsXPath($payload);
        // Note that the regular expression comes from the order create XSD when validating the order create schema.
        $this->assertRegExp('/[0-9]?[0-9]:[0-9][0-9]:[0-9][0-9]?/', $xpath->evaluate('string(x:TimeSpentOnSite)'));
    }

    protected function getRootNodeName()
    {
        return '';
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function serializeContents()
    {
        return str_replace(
            '<SessionInfo>',
            sprintf('<SessionInfo xmlns="%s">', static::XML_NS),
            $this->serializeCustomerSessionInfo()
        );
    }
}
