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

use \DOMXPath;

trait TFeeBase
{
    /** @var string */
    protected $type;
    /** @var string */
    protected $description;
    /** @var string */
    protected $id;
    /** @var IPriceGroup|ITaxedPriceGroup */
    protected $charge;

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Identifier for the fee.
     * SKU will be used for any legal fees offered in the output.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getEmptyFeePriceGroup()
    {
        return $this->buildPayloadForInterface(static::PRICEGROUP_INTERFACE);
    }

    protected function serializeContents()
    {
        return "<FeeType>{$this->xmlEncode($this->getType())}</FeeType>"
            . "<Description>{$this->xmlEncode($this->getDescription())}</Description>"
            . "<FeeId>{$this->xmlEncode($this->getId())}</FeeId>"
            . $this->getCharge()->setRootNodeName('Charge')->serialize();
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function getXmlNamespace()
    {
        return self::XML_NS;
    }

    protected function deserializeCharge(DOMXPath $xpath)
    {
        $priceNode = $xpath->query('x:Charge')->item(0);
        if ($priceNode) {
            $this->charge = $this->getEmptyFeePriceGroup()->deserialize($priceNode->C14N());
        }
    }

    /**
     * get the pricegroup payload for the fee
     * @return IPriceGroup|ITaxedPriceGroup
     */
    abstract public function getCharge();

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
