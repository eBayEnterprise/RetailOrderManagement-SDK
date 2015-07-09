<?php

namespace eBayEnterprise\RetailOrderManagement\Payload\Payment\TenderType;

interface ILookupMessage
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const XSD = '/checkout/1.0/Payment-Service-TenderTypeLookup-1.0.xsd';
}
