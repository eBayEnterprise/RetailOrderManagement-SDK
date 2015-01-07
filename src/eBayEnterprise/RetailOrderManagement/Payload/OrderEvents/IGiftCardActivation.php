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

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IGiftCardActivation extends IPayload
{
    const ROOT_NODE = 'GiftCertificate';
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';

    /**
     * get gift card action
     *
     * xsd optional
     * @return string
     */
    public function getGiftCardAction();
    /**
     * @param string
     * @return self
     */
    public function setGiftCardAction($giftCardAction);
    /**
     * get denomination of the gift card
     *
     * xsd optional
     * xsd restriction: 2 decimal, non-negative
     * @return string
     */
    public function getDenomination();
    /**
     * @param string
     * @return self
     */
    public function setDenomination($denomination);
    /**
     * get gift card code
     *
     * xsd optional
     * @return string
     */
    public function getGiftCardCode();
    /**
     * @param string
     * @return self
     */
    public function setGiftCardCode($giftCardCode);
    /**
     * get gift card secondary code
     *
     * xsd optional
     * @return string
     */
    public function getGiftCardSecondaryCode();
    /**
     * @param string
     * @return self
     */
    public function setGiftCardSecondaryCode($giftCardSecondaryCode);
    /**
     * get gift card pin
     *
     * xsd optional
     * @return string
     */
    public function getGiftCardPin();
    /**
     * @param string
     * @return self
     */
    public function setGiftCardPin($giftCardPin);
    /**
     * get gift card face identifier
     *
     * xsd optional
     * @return string
     */
    public function getGiftCardFaceIdentifier();
    /**
     * @param string
     * @return self
     */
    public function setGiftCardFaceIdentifier($giftCardFaceIdentifier);
    /**
     * get to name
     *
     * xsd optional
     * @return string
     */
    public function getTo();
    /**
     * @param string
     * @return self
     */
    public function setTo($to);
    /**
     * get from name
     *
     * xsd optional
     * @return string
     */
    public function getFrom();
    /**
     * @param string
     * @return self
     */
    public function setFrom($from);
    /**
     * get message
     *
     * xsd optional
     * @return string
     */
    public function getMessage();
    /**
     * @param string
     * @return self
     */
    public function setMessage($message);
}
