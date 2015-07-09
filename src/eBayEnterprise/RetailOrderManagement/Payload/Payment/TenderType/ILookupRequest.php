<?php

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment\TenderType;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Payment\IPaymentAccountUniqueId;

interface ILookupRequest extends ILookupMessage, IPayload, IPaymentAccountUniqueId
{
    // Tender type classes
    const CLASS_CREDITCARD = 'CreditCard';
    const CLASS_STOREDVALUE = 'StoredValue';

    /**
     * Tender type class
     *
     * @return string
     */
    public function getTenderClass();

    /**
     * @param string
     * @return self
     */
    public function setTenderClass($tenderClass);

    /**
     * The code that represents the type of currency being used for
     * a transaction. Currency codes are defined by ISO 4217:2008
     * @see http://en.wikipedia.org/wiki/ISO_4217
     *
     * restrictions: length = 3
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string
     * @return self
     */
    public function setCurrencyCode($currencyCode);
}
