<?php
/**
 * Copyright (c) 2013-2015 eBay Enterprise, Inc.
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

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

interface IPersonName
{
    /**
     * restrictions: max length 64 characters
     * @return string
     */
    public function getFirstName();

    /**
     * @param string
     * @return self
     */
    public function setFirstName($firstName);

    /**
     * restrictions: max length 64 characters
     * @return string
     */
    public function getLastName();

    /**
     * @param string
     * @return self
     */
    public function setLastName($lastName);

    /**
     * restrictions: max length 40 characters
     * @return string
     */
    public function getMiddleName();

    /**
     * @param string
     * @return self
     */
    public function setMiddleName($middleName);

    /**
     * restrictions: max length 10 characters
     * @return string
     */
    public function getHonorificName();

    /**
     * @param string
     * @return self
     */
    public function setHonorificName($honorificName);
}
