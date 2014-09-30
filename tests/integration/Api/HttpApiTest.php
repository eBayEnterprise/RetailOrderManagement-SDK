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

class HttpApiTest extends \PHPUnit_Framework_TestCase
{
    protected $apiKey;
    protected $host;
    protected $majorVersion;
    protected $minorVersion;
    protected $storeId;
    protected $service;
    protected $operation;

    public function providePaymentPayload()
    {
        return array(
            array(
                array(
                    'requestId' => '',
                    'orderId' => '',
                    'panIsToken' => '',
                    'cardNumber' => '',
                    'expirationDate' => '',
                    'securityCode' => '',
                    'amount' => '',
                    'currencyCode' => '',
                    'email' => '',
                    'ip' => '',
                    'billingAddress' => array(
                        'firstName' => '',
                        'lastName' => '',
                        'phone' => '',
                        'addressLines' => array(),
                        'city' => '',
                        'mainDivision' => '',
                        'countryCode' => '',
                        'postalCode' => ''
                    ),
                    'shippingAddress' => array(
                        'firstName' => '',
                        'lastName' => '',
                        'phone' => '',
                        'addressLines' => array(),
                        'city' => '',
                        'mainDivision' => '',
                        'countryCode' => '',
                        'postalCode' => ''
                    )
                )
            )
        );
    }

    /**
     * @dataProvider providePaymentPayload
     */

    public function testPayment($paymentPayloadData)
    {
        $config = new HttpApiConfig($this->apiKey, $this->host, $this->majorVersion, $this->minorVersion, $this->storeId, $this->service, $this->operation);
        $api = new HttpApi($config);

        $requestPayload = $api->getRequestBody();
        $requestPayload
            >setRequestId($paymentPayloadData['requestId'])
            ->setOrderId($paymentPayloadData['orderId'])
            ->setPanIsToken($paymentPayloadData['panIsToken'])
            ->setCardNumber($paymentPayloadData['cardNumber'])
            ->setExpirationDate($paymentPayloadData['expirationDate'])
            ->setCardSecurityCode($paymentPayloadData['securityCode'])
            ->setAmount($paymentPayloadData['amount'])
            ->setCurrencyCode($paymentPayloadData['currencyCode'])
            ->setEmail($paymentPayloadData['email'])
            ->setIp($paymentPayloadData['ip'])
            ->setBillingAddress($paymentPayloadData['billingAddress']);

        $api->setRequestBody($requestPayload);
        $api->send();

        $ccAuthResponse = $api->getResponseBody();
    }
}
