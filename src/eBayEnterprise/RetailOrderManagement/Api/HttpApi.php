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

require_once __DIR__.'/../../../../vendor/rmccue/requests/library/Requests.php';

/**
 * Class HttpApi
 * @package eBayEnterprise\RetailOrderManagement\Api
 */
class HttpApi implements IBidirectionalApi
{
    /** @var IConfig  */
    protected $config;
    /** @var IPayload  */
    protected $requestPayload;
    /** @var  IPayload */
    protected $replyPayload;
    /** @var  $IPayloadFactory */
    protected $payloadFactory;

    public function __construct(IConfig $config, array $args = array())
    {
        $this->config = $config;

        \Requests::register_autoloader();

        $factory = new Payload\PayloadFactory($this->config);
    }

    public function getRequestBody()
    {
        if ($this->requestPayload !== null) {
            return $this->requestPayload;
        }

        $this->requestPayload = $this->payloadFactory->requestPayload();
        return $this->requestPayload;
    }

    public function setRequestBody(Payload\IPayload $payload)
    {
        $this->requestPayload = $payload;
        return $this;
    }

    /**
     * @return Requests_Response From the Requests library
     * @throws Requests_Exception
     */
    protected function sendRequest()
    {
        return \Requests::post($this->config->getEndpoint(), $this->getRequestBody()->serialize());
    }

    public function send()
    {
        $postData = $this->getRequestBody()->serialize();

        // actually do POST
        try {
            $response = $this->sendRequest();
            if ($response->success === false) {
                throw new Exception\NetworkError("HTTP result {$response->status_code} for POST to {$response->url}.");
            }
        }
        catch (Requests_Exception $e) {
            throw new Exception\NetworkError();
        }

        $responseData = $response->body;
        $this->getResponseBody()->deserialize($responseData);

        return $this;
    }

    public function getResponseBody()
    {
        if ($this->replyPayload !== null) {
            return $this->replyPayload;
        }

        $this->replyPayload = $this->payloadFactory->replyPayload();
        return $this->replyPayload;
    }
}
