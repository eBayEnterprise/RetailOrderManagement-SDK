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

use eBayEnterprise\RetailOrderManagement\Api\Exception\UnsupportedHttpAction;
use eBayEnterprise\RetailOrderManagement\Payload;

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
    /** @var  IPayloadFactory */
    protected $payloadFactory;
    /** @var  Requests_Response Response object from the last call to Requests*/
    protected $lastRequestsResponse;

    public function __construct(IConfig $config, array $args = array())
    {
        $this->config = $config;

        \Requests::register_autoloader();

        $this->payloadFactory = new Payload\PayloadFactory($this->config);
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

    protected function buildHeader()
    {
        return [
            'apikey' => $this->config->getApiKey(),
            'Content-type' => $this->config->getContentType()
        ];
    }

    /**
     * @return Requests_Response
     * @throws Requests_Exception
     */
    protected function post()
    {
        $this->lastRequestsResponse = \Requests::post(
            $this->config->getEndpoint(),
            $this->buildHeader(),
            $this->getRequestBody()->serialize()
        );
        return $this->lastRequestsResponse->success;
    }

    /**
     * @return Requests_Response
     * @throws Requests_Exception
     */
    protected function get()
    {
        $this->lastRequestsResponse = \Requests::post(
            $this->config->getEndpoint(),
            $this->buildHeader()
        );
        return $this->lastRequestsResponse->success;
    }

    /**
     * @return boolean
     * @throws Exception\UnsupportedHttpAction
     */
    protected function sendRequest()
    {
        // clear the old response
        $this->lastRequestsResponse = null;
        $httpMethod = strtolower($this->config->getHttpMethod());
        if (!method_exists($this, $httpMethod)) {
            throw new Exception\UnsupportedHttpAction(
                sprintf(
                    'HTTP action %s not supported.',
                    strtoupper($httpMethod)
                )
            );
        }

        return $this->$httpMethod();
    }

    public function send()
    {
        $this->getRequestBody()->serialize();

        // actually do the request
        try {
            if ($this->sendRequest() === false) {
                $message = sprintf(
                    'HTTP result %s for %s to %s.\n%s',
                    $this->lastRequestsResponse->status_code,
                    $this->config->getHttpMethod(),
                    $this->lastRequestsResponse->url,
                    $this->lastRequestsResponse->body
                );
                throw new Exception\NetworkError($message);
            }
        } catch (\Requests_Exception $e) {
            // simply pass through the message but with an expected exception type - don't
            // have any request/response to include as this exception only occurs
            // when the request cannot even be attempted.
            throw new Exception\NetworkError($e->getMessage());
        }

        $responseData = $this->lastRequestsResponse->body;
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
