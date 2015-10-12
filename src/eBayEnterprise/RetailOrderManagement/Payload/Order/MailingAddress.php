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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\MailingAddress as CheckoutMailingAddress;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class MailingAddress extends CheckoutMailingAddress implements IMailingAddress
{
    use TIdentity;

    const ROOT_NODE = 'MailingAddress';

    /** @var string */
    protected $phone;

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
        parent::__construct($validators, $schemaValidator, $payloadMap, $logger, $parentPayload);

        $this->logger = $logger;
        $this->extractionPaths = array_merge(
            $this->extractionPaths,
            ['id' => 'string(@id)']
        );
        $this->optionalExtractionPaths = array_merge(
            $this->optionalExtractionPaths,
            ['phone' => 'x:Phone']
        );
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    protected function serializeContents()
    {
        return parent::serializeContents()
            . "<Phone>{$this->xmlEncode($this->getPhone())}</Phone>";
    }

    protected function getRootNodeName()
    {
        return 'MailingAddress';
    }

    protected function getRootAttributes()
    {
        return [
            'id' => $this->getId(),
        ];
    }
}
