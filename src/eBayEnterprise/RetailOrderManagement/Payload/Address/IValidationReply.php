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

namespace eBayEnterprise\RetailOrderManagement\Payload\Address;

use eBayEnterprise\RetailOrderManagement\Payload\Checkout\IPhysicalAddress;

interface IValidationReply extends IValidation, IResultAddress, ISuggestedAddressContainer, IResultErrorLocationContainer
{
    const ROOT_NODE = 'AddressValidationResponse';
    const RESULT_VALID = 'V';
    const RESULT_CORRECTED_WITH_SUGGESTIONS = 'C';
    const RESULT_FAILED = 'K';
    const RESULT_NOT_SUPPORTED = 'N';
    const RESULT_UNABLE_TO_CONTACT_PROVIDER = 'U';
    const RESULT_TIMEOUT = 'T';
    const RESULT_PROVIDER_ERROR = 'P';
    const RESULT_MALFORMED = 'M';

    /**
     * Result code of the address validation request. Indicates if the address
     * validated successfully and, if not, some indication as to why.
     * Possible values:
     * V: the address was verified - submitted address was correct or the
     *    address was standardized
     * C: data was corrected and there are suggested addresses
     * K: address was checked but could not be definitively corrected. Suggested
     *    addresses that have a higher probability of deliverability may or may
     *    not be returned.
     * N: address could not be verified by the service because the address
     *    verifier does not support the country of the address
     * U: unable to contact provider
     * T: provider timed out
     * P: provider returned a system error, only if a P is returned will the
     *    providerErrorText field be populated.
     * M: the request message was malformed or contained invalid data, such as
     *    a non existent country code
     *
     * restrictions: one of {V|C|K|N|U|T|P|M}
     * @return string
     */
    public function getResultCode();

    /**
     * @param string
     * @return self
     */
    public function setResultCode($resultCode);

    /**
     * The number of suggestions returned by the address validation service.
     *
     * @return int
     */
    public function getResultSuggestionCount();

    /**
     * @param int
     * @return self
     */
    public function setResultSuggestionCount($resultSuggestionCount);
}
