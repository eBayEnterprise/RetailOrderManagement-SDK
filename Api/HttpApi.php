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

class HttpApi implements IBidirectionalApi
{
    /** @var IConfig  */
    protected $config;
    /** @var IPayload  */
    protected $requestPayload;
    /** @var  IPayload */
    protected $replyPayload;

    public function __construct(IConfig $config, array $args = array())
    {
        $this->config = $config;

        $factory = new Payload\PayloadFactory($this->config);
        $this->requestPayload = $factory->requestPayload();
        $this->resplyPayload = $factory->replyPayload();
    }

    public function getRequestBody()
    {
        return $this->requestPayload;
    }

    public function setRequestBody(Payload\IPayload $payload)
    {
        $this->requestPayload = $payload;
        return $this;
    }

    public function send()
    {
        $postData = $this->requestPayload->serialize();

        // actually do POST

        $responseData = null;
        $this->replyPayload->deserialize($responseData);
    }

    public function getResponseBody()
    {
        return $this->replyPayload;
    }
} 