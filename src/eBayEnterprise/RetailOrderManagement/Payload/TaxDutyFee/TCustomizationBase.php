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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

trait TCustomizationBase
{
    /** @var string */
    protected $id;
    /** @var int */
    protected $customizationId;
    /** @var string */
    protected $itemId;
    /** @var string */
    protected $itemDescription;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getCustomizationId()
    {
        return $this->customizationId;
    }

    public function setCustomizationId($customizationId)
    {
        $this->customizationId = $customizationId;
        return $this;
    }

    public function getItemId()
    {
        return $this->itemId;
    }

    public function setItemId($itemId)
    {
        $this->itemId = $this->cleanString($itemId, 20);
        return $this;
    }

    public function getItemDescription()
    {
        return $this->itemDescription;
    }

    public function setItemDescription($description)
    {
        $this->itemDescription = $description;
        return $this;
    }
}
