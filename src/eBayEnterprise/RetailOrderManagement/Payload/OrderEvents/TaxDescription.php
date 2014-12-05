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

use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;

class TaxDescription implements ITaxDescription
{
    use TPayload, TAmount;

    /** @var string */
    protected $description;
    /** @var float */
    protected $amount;

    /**
     * @param IValidatorIterator
     */
    public function __construct(IValidatorIterator $validators)
    {
        $this->extractionPaths = [
            'description' => 'string(TaxDescription)',
            'amount' => 'number(TaxDescription/@amount)',
        ];
        $this->validators = $validators;
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

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount)
    {
        $this->amount = $this->sanitizeAmount($amount);
        return $this;
    }

    protected function getRootNodeName()
    {
        return static::ROOT_NODE;
    }

    protected function serializeContents()
    {
        return "<TaxDescription amount='{$this->getAmount()}'>"
            . $this->getDescription()
            . '</TaxDescription>';
    }
}
