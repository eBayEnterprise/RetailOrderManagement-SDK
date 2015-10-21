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
 * @copyright   Copyright (c) 2013-2015 eBay Enterprise, Inc. (http://www.ebayenterprise.com/)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace eBayEnterprise\RetailOrderManagement\Payload\Checkout;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\IPayloadMap;
use eBayEnterprise\RetailOrderManagement\Payload\ISchemaValidator;
use eBayEnterprise\RetailOrderManagement\Payload\IValidatorIterator;
use eBayEnterprise\RetailOrderManagement\Payload\Order\Tax as OrderTax;
use eBayEnterprise\RetailOrderManagement\Payload\PayloadFactory;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class Tax extends OrderTax implements ITax
{
    use TInvoiceTextCodeContainer;

    const ROOT_NODE = 'Tax';

    /** @var float */
    protected $exemptAmount;
    /** @var float */
    protected $nonTaxableAmount;

    /**
     * @param IValidatorIterator
     * @param ISchemaValidator
     * @param IPayloadMap
     * @param LoggerInterface
     * @param IPayload
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function __construct(
        IValidatorIterator $validators,
        ISchemaValidator $schemaValidator,
        IPayloadMap $payloadMap,
        LoggerInterface $logger,
        IPayload $parentPayload = null
    ) {
        parent::__construct($validators, $schemaValidator, $payloadMap, $logger, $parentPayload);

        $this->logger = $logger;
        $this->payloadMap = $payloadMap;
        $this->payloadFactory = new PayloadFactory;

        $this->optionalExtractionPaths = array_merge(
            $this->optionalExtractionPaths,
            [
                'exemptAmount' => 'x:ExemptAmount',
                'nonTaxableAmount' => 'x:NonTaxableAmount',
            ]
        );
        $this->subpayloadExtractionPaths = [
            'invoiceTextCodes' => 'x:InvoiceTextCodes',
        ];

        $this->invoiceTextCodes = $this->buildPayloadForInterface(self::INVOICE_TEXT_CODE_ITERABLE_INTERFACE);
    }

    /**
     * Amount of item amount not subject to taxes due to tax exempt status.
     *
     * @return float
     */
    public function getExemptAmount()
    {
        return $this->exemptAmount;
    }

    /**
     * @param float
     * @return self
     */
    public function setExemptAmount($exemptAmount)
    {
        $this->exemptAmount = $this->sanitizeAmount($exemptAmount);
        return $this;
    }

    /**
     * Amount of item amount not subject to taxes due to non-taxable status.
     *
     * @return float
     */
    public function getNonTaxableAmount()
    {
        return $this->nonTaxableAmount;
    }

    /**
     * @param float
     * @return self
     */
    public function setNonTaxableAmount($nonTaxableAmount)
    {
        $this->nonTaxableAmount = $this->sanitizeAmount($nonTaxableAmount);
        return $this;
    }

    protected function serializeContents()
    {
        return "<Situs>{$this->xmlEncode($this->getSitus())}</Situs>"
            . $this->serializeJurisdiction()
            . $this->serializeImposition()
            . $this->serializeEffectiveRate()
            . $this->serializeOptionalAmount('TaxableAmount', $this->getTaxableAmount())
            . $this->serializeOptionalAmount('ExemptAmount', $this->getExemptAmount())
            . $this->serializeOptionalAmount('NonTaxableAmount', $this->getNonTaxableAmount())
            . $this->serializeAmount('CalculatedTax', $this->getCalculatedTax())
            . $this->serializeOptionalXmlEncodedValue('SellerRegistrationId', $this->getSellerRegistrationId())
            . $this->getInvoiceTextCodes()->serialize();
    }
}
