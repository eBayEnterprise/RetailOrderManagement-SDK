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

use BadMethodCallException;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TIterablePayload;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SPLObjectStorage;

class OrderDestinationIterable extends SPLObjectStorage implements IOrderDestinationIterable
{
    use TIterablePayload;

    const SUBPAYLOAD_XPATH = 'x:MailingAddress|x:StoreLocation|x:Email';
    const ROOT_NODE = 'Destinations';

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
        $this->payloadMap = $payloadMap;
        $this->parentPayload = $parentPayload;
        $this->payloadFactory = new PayloadFactory;
    }

    public function deserialize($serializedData)
    {
        $xpath = $this->getPayloadAsXPath($serializedData);
        foreach ($xpath->query($this->getSubpayloadXPath()) as $subpayloadNode) {
            switch ($subpayloadNode->nodeName) {
                case 'MailingAddress':
                    $pl = $this->getEmptyMailingAddress();
                    break;
                case 'StoreLocation':
                    $pl = $this->getEmptyStoreLocation();
                    break;
                case 'Email':
                    $pl = $this->getEmptyEmailAddress();
                    break;
            }
            if (isset($pl)) {
                $pl->deserialize($subpayloadNode->C14N());
                $this->offsetSet($pl);
            }
        }
        $this->validate();
        return $this;
    }

    protected function serializeContents()
    {
        // Destinations must be ordered by type. Build out three separate
        // serializations, one for each type, to keep destinations of a certain
        // type together.
        $mailingDestinations = '';
        $storeLocations = '';
        $emailDestinations = '';
        foreach ($this as $destination) {
            if ($destination instanceof IEmailAddressDestination) {
                $emailDestinations .= $destination->serialize();
            } elseif ($destination instanceof IStoreLocation) {
                $storeLocations .= $destination->serialize();
            } else {
                $mailingDestinations .= $destination->serialize();
            }
        }
        // Concatenate the serializations together in the required order.
        return $mailingDestinations . $storeLocations . $emailDestinations;
    }

    public function getNewSubpayload()
    {
        throw new BadMethodCallException('Method not supported for this type.');
    }

    /**
     * Get a new, empty mailing address destination object.
     *
     * @return IMailingAddress
     */
    public function getEmptyMailingAddress()
    {
        return $this->buildPayloadForInterface(static::MAILING_ADDRESS_INTERFACE);
    }

    /**
     * Get a new, empty store location destination object.
     *
     * @return IStoreLocation
     */
    public function getEmptyStoreLocation()
    {
        return $this->buildPayloadForInterface(static::STORE_LOCATION_INTERFACE);
    }

    /**
     * Get a new, empty email address destination object.
     *
     * @return IEmailAddressDestination
     */
    public function getEmptyEmailAddress()
    {
        return $this->buildPayloadForInterface(static::EMAIL_ADDRESS_INTERFACE);
    }

    public function getSubpayloadXPath()
    {
        return static::SUBPAYLOAD_XPATH;
    }

    public function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    public function getXmlNamespace()
    {
        return self::XML_NS;
    }
}
