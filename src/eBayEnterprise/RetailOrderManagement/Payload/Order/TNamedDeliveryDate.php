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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order;

use DateTime;

trait TNamedDeliveryDate
{
    /** @var DateTime */
    protected $namedDeliveryDate;
    /** @var DateTime */
    protected $namedDeliveryTimeWindowStart;
    /** @var DateTime */
    protected $namedDeliveryTimeWindowEnd;
    /** @var string */
    protected $namedDeliveryMessage;

    public function getNamedDeliveryDate()
    {
        return $this->namedDeliveryDate;
    }

    public function setNamedDeliveryDate(DateTime $namedDeliveryDate)
    {
        $this->namedDeliveryDate = $namedDeliveryDate;
        return $this;
    }

    public function getNamedDeliveryTimeWindowStart()
    {
        return $this->namedDeliveryTimeWindowStart;
    }

    public function setNamedDeliveryTimeWindowStart(DateTime $namedDeliveryTimeWindowStart)
    {
        $this->namedDeliveryTimeWindowStart = $namedDeliveryTimeWindowStart;
        return $this;
    }

    public function getNamedDeliveryTimeWindowEnd()
    {
        return $this->namedDeliveryTimeWindowEnd;
    }

    public function setNamedDeliveryTimeWindowEnd(DateTime $namedDeliveryTimeWindowEnd)
    {
        $this->namedDeliveryTimeWindowEnd = $namedDeliveryTimeWindowEnd;
        return $this;
    }

    public function getNamedDeliveryMessage()
    {
        return $this->namedDeliveryMessage;
    }

    public function setNamedDeliveryMessage($namedDeliveryMessage)
    {
        $this->namedDeliveryMessage = $namedDeliveryMessage;
        return $this;
    }

    /**
     * Serialize the named deliver date data into XML.
     *
     * @return string
     */
    protected function serializeNamedDeliveryDate()
    {
        $message = $this->getNamedDeliveryMessage();
        // Must contain a message to create a valid serialization.
        if (!is_null($message)) {
            return '<NamedDeliveryDate>'
                . $this->serializeOptionalDateValue('DeliveryDate', 'Y-m-d', $this->getNamedDeliveryDate())
                . $this->serializeOptionalDateValue(
                    'TimeWindowStart',
                    'H:i:sP',
                    $this->getNamedDeliveryTimeWindowStart()
                )
                . $this->serializeOptionalDateValue('TimeWindowEnd', 'H:i:sP', $this->getNamedDeliveryTimeWindowEnd())
                . "<Message>{$this->xmlEncode($message)}</Message>"
                . '</NamedDeliveryDate>';
        }
        return '';
    }

    /**
     * encode the passed in string to be safe for xml if it is not null,
     * otherwise simply return the null parameter.
     *
     * @param string|null
     * @return string|null
     */
    abstract protected function xmlEncode($value = null);
}
