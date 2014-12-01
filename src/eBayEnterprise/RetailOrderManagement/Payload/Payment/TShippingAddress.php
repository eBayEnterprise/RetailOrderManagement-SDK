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

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

trait TShippingAddress
{
    use TPhysicalAddress {
        getLines as public getShipToLines;
        setLines as public setShipToLines;
        getCity as public getShipToCity;
        setCity as public setShipToCity;
        getMainDivision as public getShipToMainDivision;
        setMainDivision as public setShipToMainDivision;
        getCountryCode as public getShipToCountryCode;
        setCountryCode as public setShipToCountryCode;
        getPostalCode as public getShipToPostalCode;
        setPostalCode as public setShipToPostalCode;
    }
    /**
     * Serialize a Shipping Address
     * @return string
     */
    protected function serializeShippingAddress($addressStatus = null)
    {
        $lines = 0;
        $node = '<ShippingAddress>';
        $linesArray = preg_split("/\n/", $this->getShipToLines(), 4, PREG_SPLIT_NO_EMPTY);
        foreach ($linesArray as $shipToLine) {
            $lines++;
            $node .= "<Line{$lines}>{$shipToLine}</Line{$lines}>";
        }
        $node .= "<City>{$this->getShipToCity()}</City>"
            . "<MainDivision>{$this->getShipToMainDivision()}</MainDivision>"
            . "<CountryCode>{$this->getShipToCountryCode()}</CountryCode>"
            . "<PostalCode>{$this->getShipToPostalCode()}</PostalCode>";
        if ($addressStatus) {
            $node .= "<AddressStatus>{$addressStatus}</AddressStatus>";
        }
        $node .= "</ShippingAddress>";
        return $node;
    }
}
