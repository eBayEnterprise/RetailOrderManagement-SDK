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

class Subpayloads implements IValidator
{
    /** @var string[] */
    protected $subpayloadAccessors;

    /**
     * @param string[] Accessor methods to get subpayloads of the payload being validated
     */
    public function __construct(array $subpayloadAccessors = [])
    {
        $this->subpayloadAccessors = $subpayloadAccessors;
    }
    /**
     * Validate each payload returned from by the accessor methods provided
     * to the validator. If any payload is invalid or any payload accessor
     * produces something other than a payload, the validated payload should
     * be considered invalid.
     *
     * @param  IPayload $payload
     * @return self
     * @throws Exception\InvalidPayload
     */
    public function validate(IPayload $payload)
    {
        foreach ($this->subpayloadAccessors as $accessorMethod) {
            $this->validatePayload($payload->$accessorMethod());
        }
        return $this;
    }

    /**
     * Ensure the subpayload is a valid IPayload.
     *
     * @return self
     */
    protected function validatePayload(IPayload $payload)
    {
        $payload->validate();
        return $this;
    }
}
