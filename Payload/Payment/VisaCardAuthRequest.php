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

/**
 * <code>
 * $visaAuthRequest = new VisaCardAuthRequest();
 * $visaAuthRequest
 *     ->setOrderId($orderId)
 *     ->setPrimaryAccountNumber($panToken)
 *     ->setPanIsToken(true)
 *     ->setExpirationDate($expDate)
 *     ->setCardSecurityCode($cvv)
 *     ->setAmountToAuth($amt)
 *     ->setBillingLastName($billingLastName)
 *     ->setBillingFirstName($billingFirstName)
 *     ->setBillingPhoneNumber($billingPhoneNumber)
 *     ->setBillingAddress($billingAddress)
 *     ->setCustomerEmail($customerEmail)
 *     ->setCustomerIpAddress($customerIpAddress)
 *     ->setShipToLastName($shipToLastName)
 *     ->setShipToFirstName($shipToFirstName)
 *     ->setShipToPhoneNumber($shipToPhoneNumber)
 *     ->setShippingAddress($shippingAddress)
 *     ->setIsRequestToCorrectCvvOrAvsError(false)
 *     ->setSecureVerificationData($verification);
 * try {
 *     $visaAuthResponse = $visaAuthRequest->send();
 * } catch (CreditCardAuthIncompleteException $e) {
 *
 * } catch (CreditCardAuthFailedException $e) {
 *
 * } catch (HttpException $e) {
 *
 * } finally {
 *     ...
 * }
 * </code>
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;

class VisaCardAuthRequest extends CreditCardAuthRequest implements ICreditCardAuthRequest
{
    /**
     * @var string $tenderType
     * @see IConfig::getTenderType
     */
    protected $tenderType;
    /**
     * @var string $orderId A unique identifier for the order;
     *
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     */
    protected $orderId;
    /** @var string $primaryAccountNumber */
    protected $primaryAccountNumber;
    /** @var bool $panIsToken Indicates if the Payment Account Number (PAN) is the actual number, or a representation of the number. */
    protected $panIsToken;
    protected $expirationDate;
    protected $cardSecurityCode;
    protected $amountToAuth;
    protected $billingLastName;
    protected $billingFirstName;
    protected $billingPhoneNumber;
    protected $billingAddress;
    protected $customerEmail;
    protected $customerIpAddress;
    protected $shipToFirstName;
    protected $shipToLastName;
    protected $shipToPhoneNumber;
    protected $shippingAddress;
    protected $isRequestToCorrectCvvOrAvsError;
    protected $secureVerificationData;
    protected $requestId;

}
