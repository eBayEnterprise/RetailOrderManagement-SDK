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
use DOMXPath;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

trait TIterablePayload
{
    use TPayload;

    /** @var bool */
    protected $includeIfEmpty = false;
    /** @var bool */
    protected $buildRootNode = true;

    public function serialize()
    {
        $format = $this->buildRootNode ? '<%1$s>%2$s</%1$s>' : '%2$s';
        $serializedSubpayloads = $this->serializeContents();
        return ($this->includeIfEmpty || $serializedSubpayloads)
            ? sprintf($format, $this->getRootNodeName(), $serializedSubpayloads)
            : '';
    }

    public function deserialize($serializedData)
    {
        $xpath = $this->getPayloadAsXPath($serializedData);
        foreach ($xpath->query($this->getSubpayloadXPath()) as $subpayloadNode) {
            $pl = $this->getNewSubpayload()->deserialize($subpayloadNode->C14N());
            $this->offsetSet($pl);
        }
        $this->validate();
        return $this;
    }

    protected function serializeContents()
    {
        $serializedSubpayloads = '';
        foreach ($this as $subpayload) {
            $serializedSubpayloads .= $subpayload->serialize();
        }
        return $serializedSubpayloads;
    }

    /**
     * Get an XPath expression that will separate the serialized data into
     * XML for each subpayload in the iterable.
     *
     * @return string
     */
    abstract protected function getSubpayloadXPath();

    /**
     * Get a new, empty instance of payloads the iterable contains.
     *
     * @return IPayload
     */
    abstract protected function getNewSubpayload();
}
