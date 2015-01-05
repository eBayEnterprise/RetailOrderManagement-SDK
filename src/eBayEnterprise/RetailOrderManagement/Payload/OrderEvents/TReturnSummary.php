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

trait TReturnSummary
{
    /** @var string */
    protected $referenceNumber;
    /** @var float */
    protected $totalCredit;
    /** @var string */
    protected $returnOrCredit;

    public function getReferenceNumber()
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber($referenceNumber)
    {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    public function getTotalCredit()
    {
        return $this->totalCredit;
    }

    public function setTotalCredit($totalCredit)
    {
        $this->totalCredit = $this->sanitizeAmount($totalCredit);
        return $this;
    }

    public function getReturnOrCredit()
    {
        return $this->returnOrCredit;
    }

    public function setReturnOrCredit($returnOrCredit)
    {
        $this->returnOrCredit = $returnOrCredit;
        return $this;
    }

    public function isReturn()
    {
        return strtolower($this->returnOrCredit) === 'return';
    }

    public function isCredit()
    {
        return strtolower($this->returnOrCredit) === 'credit';
    }

    /**
     * ensure the amount is rounded to two decimal places.
     * @param  mixed $amount any numeric value
     * @return float $amount rounded to 2 places.
     * @return null  if $amount is not numeric
     */
    abstract protected function sanitizeAmount($amount);
}
