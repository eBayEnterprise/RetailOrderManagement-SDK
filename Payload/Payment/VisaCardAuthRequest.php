<?php
/**
 * Created by PhpStorm.
 * User: smithm5
 * Date: 9/11/14
 * Time: 1:55 PM
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