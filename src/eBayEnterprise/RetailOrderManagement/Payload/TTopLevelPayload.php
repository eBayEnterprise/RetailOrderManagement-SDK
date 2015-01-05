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

namespace eBayEnterprise\RetailOrderManagement\Payload;

use DOMXPath;

trait TTopLevelPayload
{
    use TPayload;

    /**
     * Validate the serialized data via the schema validator.
     * @param  string $serializedData
     * @return $this
     */
    protected function schemaValidate($serializedData)
    {
        if ($this->schemaValidator) {
            $this->schemaValidator->validate($serializedData, $this->getSchemaFile());
        }
        return $this;
    }

    /**
     * Name, value pairs of root attributes
     *
     * @return array
     */
    protected function getRootAttributes()
    {
        return [
            'xmlns' => $this->getXmlNamespace(),
        ];
    }

    /**
     * Return the schema file path.
     *
     * @return string
     */
    abstract protected function getSchemaFile();

    /**
     * Get path to the shared schema directory.
     *
     * @return string
     */
    protected function getSchemaDir()
    {
        return __DIR__ . '/../Schemas';
    }
}
