<?php

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment\TenderType;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;

interface ILookupReply extends ILookupMessage, IPayload
{
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
