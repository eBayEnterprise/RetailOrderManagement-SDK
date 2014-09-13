<?php

namespace eBayEnterprise\RetailOrderManagement\Transport;

interface IConfig
{
    /**
     * Return the tender type code that corresponds to the parameter.
     *
     * @param mixed $tenderType
     * @return string tender type code that corresponds to the parameter
     */
    function getTenderTypeCode($tenderType);
}