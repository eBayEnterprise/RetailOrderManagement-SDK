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

namespace eBayEnterprise\RetailOrderManagement\Payload\OrderEvents;

trait TShopRunnerMessage
{
    /** @var string */
    protected $shopRunnerMessage;

    public function getShopRunnerMessage()
    {
        return $this->shopRunnerMessage;
    }

    public function setShopRunnerMessage($shopRunnerMessage)
    {
        $this->shopRunnerMessage = $this->normalizeWhitespace($shopRunnerMessage);
        return $this;
    }

    protected function serializeShopRunnerMessage()
    {
        $message = $this->getShopRunnerMessage();
        return $message
            ? "<ShopRunnerMessage>$message</ShopRunnerMessage>"
            : '';
    }

    /**
     * Normalize any whitespace characters, tab, new line, etc., with a single
     * space caracter. Does not collapse whitespace.
     *
     * @param string
     * @return string
     */
    abstract protected function normalizeWhitespace($string);
}
