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

namespace eBayEnterprise\RetailOrderManagement\Payload\Validator;

use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IValidator;

class IterablePayload implements IValidator
{
    public function validate(IPayload $payload)
    {
        // Payload must also be an Iterator to be valid - impossible to be
        // a valid iterable payload if the payload is not iterable.
        if (!$payload instanceof \Iterator) {
            throw new Exception\InvalidPayload(sprintf('%s Payload is not iterable.', get_class($payload)));
        }
        foreach ($payload as $innerPayload) {
            $innerPayload->validate();
        }
        return $this;
    }
}
