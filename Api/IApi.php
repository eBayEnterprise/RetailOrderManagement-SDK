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

namespace eBayEnterprise\RetailOrderManagement\Api;
use eBayEnterprise\RetailOrderManagement\Payload;

interface IApi
{
    /**
     * Configure the api by supplying an object that informs
     * what payload object to use, what URI to send to, etc.
     *
     * @param IConfig|null $config
     * @param array $args
     */
    function __construct(IConfig $config=null, array $args=array());
    /**
     * Get the request payload object.
     * Initially, create and return a new empty payload
     * of the type of payload for the configured service.
     * (Users should not rely on the mutability of the returned object;
     * Use `setRequestBody` to ensure a payload is attached for sending.)
     *
     * @return Payload\IPayload
     */
    function getRequestBody();
    /**
     * Set the payload for the configured request.
     * This is the only way to guarantee an api has
     * a payload to send.
     *
     * @param Payload\IPayload $payload
     * @return self
     */
    function setRequestBody(Payload\IPayload $payload);
    /**
     * Send the request.
     * May validate the payload before sending.
     * In some unidirectional implementations this may do nothing.
     *
     * @throws Payload\Exception\InvalidPayload
     * @throws NetworkError
     * @return self
     */
    function send();
    /**
     * Retrieve the response payload.
     * May validate the payload before delivering.
     *
     * @return Payload\IPayloadIterator
     */
    function getResponseBodyIterator();
}