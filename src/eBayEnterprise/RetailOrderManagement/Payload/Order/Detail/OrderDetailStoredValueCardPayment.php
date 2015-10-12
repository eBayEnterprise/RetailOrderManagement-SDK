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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use DateTime;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\TAmount;
use eBayEnterprise\RetailOrderManagement\Payload\TPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Order\StoredValueCardPayment;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class OrderDetailStoredValueCardPayment extends StoredValueCardPayment implements IOrderDetailStoredValueCardPayment
{
    protected function serializeContents()
    {
        return $this->serializePaymentContext()
            . $this->serializePaymentRequestId()
            . $this->serializeOptionalXmlEncodedValue('Pin', $this->getPin())
            . $this->serializeAmount('Amount', $this->getAmount())
            . $this->getCustomAttributes()->serialize();
    }
}
