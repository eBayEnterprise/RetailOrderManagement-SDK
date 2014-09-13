<?php
/**
 * Created by PhpStorm.
 * User: smithm5
 * Date: 9/12/14
 * Time: 9:48 AM
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment;
use eBayEnterprise\RetailOrderManagement\Payload\ISerializable;

/**
 * Interface IPaymentContextBase
 * @package eBayEnterprise\RetailOrderManagement\Payload\Payment
 */
interface IPaymentContextBase extends ISerializable
{
    /**
     * A unique identifier for the order
     * The client is responsible for ensuring uniqueness across all transactions the client initiates with this service.
     *
     * @return IOrderId
     */
    function getOrderId();
}