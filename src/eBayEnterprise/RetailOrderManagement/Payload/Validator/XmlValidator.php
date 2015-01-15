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

namespace eBayEnterprise\RetailOrderManagement\Payload\Validator;

use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\Exception;
use DOMDocument;

class XmlValidator implements ISchemaValidator
{
    /**
     * Simple ensure the serialized data is well formed XML.
     * @param string
     * @param string|null Not used.
     * @return self
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function validate($serializedData, $schema = null)
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->loadXML($serializedData);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        if ($errors) {
            $messages = implode(', ', array_filter(array_map(
                function ($libxmlError) {
                    return trim($libxmlError->message);
                },
                $errors
            )));
            throw new Exception\InvalidPayload("Serialized data is invalid XML: $messages");
        }
        return $this;
    }
}
