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

trait TPersonName
{
    /** @var string */
    protected $firstName;
    /** @var string */
    protected $lastName;
    /** @var string */
    protected $middleName;
    /** @var string */
    protected $honorificName;

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = $this->cleanString($firstName, 64);
        return $this;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = $this->cleanString($lastName, 64);
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middleName;
    }

    public function setMiddleName($middleName)
    {
        $this->middleName = $this->cleanString($middleName, 40);
        return $this;
    }

    public function getHonorificName()
    {
        return $this->honorificName;
    }

    public function setHonorificName($honorificName)
    {
        $this->honorificName = $this->cleanString($honorificName, 10);
        return $this;
    }

    protected function serializePersonName()
    {
        $rootNode = $this->getPersonNameRootNodeName();
        return "<$rootNode>"
            . ($this->getHonorificName() ? "<Honorific>{$this->getHonorificName()}</Honorific>" : '')
            . "<LastName>{$this->getLastName()}</LastName>"
            . ($this->getMiddleName() ? "<MiddleName>{$this->getMiddleName()}</MiddleName>" : '')
            . "<FirstName>{$this->getFirstName()}</FirstName>"
            . "</$rootNode>";
    }

    /**
     * XML element node name wrapping the person name elements.
     *
     * @return string
     */
    abstract protected function getPersonNameRootNodeName();
}
