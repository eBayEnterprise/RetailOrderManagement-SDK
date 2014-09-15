<?php

namespace eBayEnterprise\RetailOrderManagement\Transport;

interface IConfig
{
    /**
     * The url for interacting with the Retail Order Management API
     *
     * @return string
     */
    function getUrl();

}
