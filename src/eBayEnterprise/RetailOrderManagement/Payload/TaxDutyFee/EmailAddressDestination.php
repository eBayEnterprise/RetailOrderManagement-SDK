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

namespace eBayEnterprise\RetailOrderManagement\Payload\TaxDutyFee;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\TPersonName;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\Order\EmailAddressDestination as OrderEmailAddressDestination;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EmailAddressDestination extends OrderEmailAddressDestination implements IEmailAddressDestination
{
    const ROOT_NODE = 'Email';
    const PERSON_NAME_ROOT_NODE = 'Customer';

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
            'firstName' => 'x:Customer/x:FirstName',
            'lastName' => 'x:Customer/x:LastName',
            'middleName' => 'x:Customer/x:MiddleName',
            'honorificName' => 'x:Customer/x:Honorific',
        ];
    }

    protected function serializeContents()
    {
        return ($this->getLastName() && $this->getFirstName() ? $this->serializePersonName() : '')
            . "<EmailAddress>{$this->xmlEncode($this->getEmailAddress())}</EmailAddress>";
    }
}
