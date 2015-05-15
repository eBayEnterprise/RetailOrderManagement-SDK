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

namespace eBayEnterprise\RetailOrderManagement\Payload\Inventory;

use eBayEnterprise\RetailOrderManagement\Payload\TIdentity;

trait TItem
{
    use TIdentity {
        getId as protected;
        setId as protected;
    }

    /** @var string */
    protected $itemId;

    /**
     * Item identifier, typically a SKU.
     *
     * restrictions: 1 <= length <= 20
     * @return string
     */
    public function getItemId()
    {
        return $this->itemId;
    }

    /**
     * @param string
     * @return self
     */
    public function setItemId($itemId)
    {
        $this->itemId = $this->cleanString($itemId, 20);
        return $this;
    }

    /**
     * Unique identifier for the item within a request.
     *
     * Assumes that all ids have been prefixed with a single
     * character. When retrieving the line id, remove the prefix
     * added when setting the line id.
     *
     * @return string
     */
    public function getLineId()
    {
        // TIdentity's getId returns a prefixed id value (prefixes
        // added whenever setting a line id in a payload). Strip off
        // the prefix added by the SDK to return the original id
        // value provided to the payload.
        return substr($this->getId(), 1);
    }

    /**
     * @param string
     * @return self
     */
    public function setLineId($lineId)
    {
        if ($lineId) {
            // When setting line ids, prefix any value provided
            // with a single, non-numeric character. While permitted by
            // the XSD, downstream systems do not allow a line id to
            // start with a numeric character. Consistent prefixing
            // ensures that all ids may also have the first character
            // removed when being retrieved to give back the original
            // id value.
            $lineId = $this->cleanString('_' . $lineId, 40);
        }
        // TIdentity's setId is used to store the processed value.
        $this->setId($lineId);
        return $this;
    }

    /**
     * Trim any white space and return the resulting string truncating to $maxLength.
     *
     * Return null if the result is an empty string or not a string
     *
     * @param string
     * @param int
     * @return string|null
     */
    abstract protected function cleanString($string, $maxLength);
}
