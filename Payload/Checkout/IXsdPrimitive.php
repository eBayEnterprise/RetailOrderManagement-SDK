<?php
/**
 * Created by PhpStorm.
 * User: smithm5
 * Date: 9/12/14
 * Time: 10:13 AM
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface IXsdPrimitive extends IPayload
{
    /**
     * The string representation of the contained anonymous XML node
     *
     * @return string
     */
    function __toString();
}
