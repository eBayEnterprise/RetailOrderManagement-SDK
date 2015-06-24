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

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPersonName;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EmailAddressDestination implements IEmailAddressDestination
{
    use TPayload, TIdentity, TPersonName;

    const ROOT_NODE = 'Email';
    const PERSON_NAME_ROOT_NODE = 'PersonName';

    /** @var string */
    protected $emailAddress;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        $this->logger = $logger;
        $this->validators = $validators;
        $this->parentPayload = $parentPayload;

        $this->extractionPaths = [
            'id' => 'string(@id)',
            'emailAddress' => 'string(x:EmailAddress)',
        ];
        $this->optionalExtractionPaths = [
            'firstName' => 'x:PersonName/x:FirstName',
            'lastName' => 'x:PersonName/x:LastName',
            'middleName' => 'x:PersonName/x:MiddleName',
            'honorificName' => 'x:PersonName/x:Honorific',
        ];
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $this->cleanString($emailAddress, 70);
        return $this;
    }

    protected function serializeContents()
    {
        return "<EmailAddress>{$this->xmlEncode($this->getEmailAddress())}</EmailAddress>"
            . ($this->getLastName() && $this->getFirstName() ? $this->serializePersonName() : '');
    }

    protected function getRootAttributes()
    {
        return ['id' => $this->getId()];
    }

    protected function getPersonNameRootNodeName()
    {
        return static::PERSON_NAME_ROOT_NODE;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
