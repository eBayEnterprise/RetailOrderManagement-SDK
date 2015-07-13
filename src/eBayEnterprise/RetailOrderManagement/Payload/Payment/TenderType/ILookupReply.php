<?php

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment\TenderType;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ILookupReply extends ILookupMessage, IPayload
{
    // response codes
    const TENDER_TYPE_FOUND = 'TENDER_TYPE_FOUND';
    const PAN_FAILS_LUHN_CHECK = 'PAN_FAILS_LUHN_CHECK';
    const NO_TENDER_TYPE_FOUND = 'NO_TENDER_TYPE_FOUND';
    const PAN_NOT_CONFIGURED_TO_STORE = 'PAN_NOT_CONFIGURED_TO_STORE';
    const UNKNOWN_FAILURE = 'UNKNOWN_FAILURE';

    /**
     * Tender type
     *
     * @return string
     */
    public function getTenderType();

    /**
     * @param string
     * @return self
     */
    public function setTenderType($tenderType);

    /**
     * code indicating the result of the request
     *
     * @return string
     */
    public function getResponseCode();

    /**
     * @param string
     * @return self
     */
    public function setResponseCode($responseCode);

    /**
     * get the message associated with the response code
     *
     * @return string
     */
    public function getResponseMessage();

    /**
     * return true if the tender type was found successfully
     *
     * @return bool
     */
    public function isSuccessful();
}
