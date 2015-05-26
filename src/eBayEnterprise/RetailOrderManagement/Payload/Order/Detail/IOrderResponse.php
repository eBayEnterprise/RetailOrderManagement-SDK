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

namespace eBayEnterprise\RetailOrderManagement\Payload\Order\Detail;

use eBayEnterprise\RetailOrderManagement\Payload\IPayload;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IFeeContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\IItemRelationshipContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\ICustomAttributeContainer;
use eBayEnterprise\RetailOrderManagement\Payload\Order\ITemplateContainer;
use DateTime;

interface IOrderResponse extends IOrderDetailItemContainer, IFeeContainer, IItemRelationshipContainer, IShipmentContainer, ICustomAttributeContainer, ITemplateContainer, IExchangeOrderContainer, IChargeGroupContainer, IPayload
{
    const XML_NS = 'http://api.gsicommerce.com/schema/checkout/1.0';
    const ROOT_NODE = 'Order';
    const CUSTOMER_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IOrderDetailCustomer';
    const SHIPPING_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IOrderDetailShipping';
    const PAYMENT_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IOrderDetailPayment';
    const ASSOCIATE_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\IAssociate';
    const TAX_HEADER_INTERFACE =
        '\eBayEnterprise\RetailOrderManagement\Payload\Order\Detail\ITaxHeader';

    /**
     * The unique text string that identifies an order.
     * Allowable Values: Text string
     * Required: Yes
     * Length: 24
     * Default Value: blank
     * Restrictions: This string must be unique within the web-store.
     *
     * @return string
     */
    public function getCustomerOrderId();

    /**
     * @param  string
     * @return self
     */
    public function setCustomerOrderId($customerOrderId);

    /**
     * Level of service can be Regular or Rush and is used to identify the priority/urgency with which the order must be processed.
     * Allowable Values:REGULAR, RUSH
     * Required: No
     * Default Value: REGULAR
     *
     * @return string
     */
    public function getLevelOfService();

    /**
     * @param  string
     * @return self
     */
    public function setLevelOfService($levelOfService);

    /**
     * Indicates if this order has chained or related items/orders. This attribute is
     * expected only in response as chained orders get spawned off in OMS. Typical use
     * case is when an order with drop-ship items gets released in OMS, it would then
     * spawn off chained orders that are related to the original order.
     *
     * @return bool
     */
    public function getHasChainedLines();

    /**
     * @param  bool
     * @return self
     */
    public function setHasChainedLines($hasChainedLines);

    /**
     * Indicates if this order has orders derived from it. This attribute is expected only
     * in response as derived orders get created in OMS. Typical use case is when an item
     * is returned in an order. This would create a return order that is related to the
     * original order.
     *
     * @return bool
     */
    public function getHasDerivedChild();

    /**
     * @param  bool
     * @return self
     */
    public function setHasDerivedChild($hasDerivedChild);

    /**
     *
     * @return string
     */
    public function getSourceId();

    /**
     * @param  string
     * @return self
     */
    public function setSourceId($sourceId);

    /**
     *
     * @return string
     */
    public function getSourceIdType();

    /**
     * @param  string
     * @return self
     */
    public function setSourceIdType($sourceIdType);


    /**
     * Contains customer information including name, gender and birth date.
     * Also includes tax-exemption information and loyalty program information.
     *
     * @return IOrderDetailCustomer
     */
    public function getCustomer();

    /**
     * @param  IOrderDetailCustomer
     * @return self
     */
    public function setCustomer(IOrderDetailCustomer $customer);

    /**
     * The time the order generating application accepted the order. There can be a time delay
     * between when the order is submitted to when it is accepted by the OMS, so this cannot
     * be stamped by either the Order Service or the OMS.
     *
     * @return DateTime
     */
    public function getCreateTime();

    /**
     * @param  DateTime
     * @return self
     */
    public function setCreateTime(DateTime $createTime);

    /**
     * @return IOrderDetailShipping
     */
    public function getShipping();

    /**
     * @param  IOrderDetailShipping
     * @return self
     */
    public function setShipping(IOrderDetailShipping $shipping);

    /**
     * @return IOrderDetailPayment
     */
    public function getPayment();

    /**
     * @param  IOrderDetailPayment
     * @return self
     */
    public function setPayment(IOrderDetailPayment $payment);

    /**
     * A promotional text message to display to the customer.
     * Allowable Values: Normalized text string.
     * Required: Yes
     * Length: TBD
     * Default Value: blank
     *
     * @return string
     */
    public function getShopRunnerMessage();

    /**
     * @param  string
     * @return self
     */
    public function setShopRunnerMessage($shopRunnerMessage);

    /**
     * The code that represents the type of currency being used for a transaction.
     * Currency codes are defined by ISO 4217:2008
     * @link http://en.wikipedia.org/wiki/ISO_4217
     * Allowable Values: IS-4217 three-letter code.
     * Required: Only when a transaction amount is specified..
     * Length: 3
     * Default Value: blank
     *
     * @return string
     */
    public function getCurrency();

    /**
     * @param  string
     * @return self
     */
    public function setCurrency($currency);

    /**
     * Information about the person who made, or facilitated the sale.
     * Most commonly used when a sales associate in a store places an
     * order for a customer if merchandise is not in stock.
     *
     * @return IAssociate
     */
    public function getAssociate();

    /**
     * @param  IAssociate
     * @return self
     */
    public function setAssociate(IAssociate $associate);

    /**
     * @return ITaxHeader
     */
    public function getTaxHeader();

    /**
     * @param  ITaxHeader
     * @return self
     */
    public function setTaxHeader(ITaxHeader $taxHeader);

    /**
     * Optional field to capture the catalog code found in some printed catalogs
     *
     * @return string
     */
    public function getPrintedCatalogCode();

    /**
     * @param  string
     * @return self
     */
    public function setPrintedCatalogCode($printedCatalogCode);

    /**
     * Allowable Values: ISO-639 two-letter language code, an underscore (_) and ISO-3166 two-letter code, for example: en_US
     * Required:Yes
     * Length: 5
     * Default Value: blank
     *
     * @return string
     */
    public function getLocale();

    /**
     * @param  string
     * @return self
     */
    public function setLocale($locale);

    /**
     * Optional field to capture the identification number of the representative who created an order through the "Dashboard"
     *
     * @return string
     */
    public function getDashboardRepId();

    /**
     * @param  string
     * @return self
     */
    public function setDashboardRepId($dashboardRepId);

    /**
     * Otherwise known as ORSO code in legacy systems. These fields are commonly used to
     * track where the customer/order originated.
     * @link http://confluence.tools.us.gspt.net/display/v11upgde/Determine+ORSO+work
     *
     * @return string
     */
    public function getOrderSource();

    /**
     * @param  string
     * @return self
     */
    public function setOrderSource($orderSource);

    /**
     * @return string
     */
    public function getOrderSourceType();

    /**
     * @param  string
     * @return self
     */
    public function setOrderSourceType($orderSourceType);

    /**
     * Status will be provided by the OMS and only present for order history. Only applies to OrderDetailResponse.
     *
     * @return string
     */
    public function getStatus();

    /**
     * @param  string
     * @return self
     */
    public function setStatus($status);

    /**
     * Optional field passed by the web-store that contains the URL for their order history page.
     * OMS will store this and publish it in the canonical email for Order.
     *
     * @return string
     */
    public function getOrderHistoryUrl();

    /**
     * @param  string
     * @return self
     */
    public function setOrderHistoryUrl($orderHistoryUrl);

    /**
     * @return bool
     */
    public function getVatInclusivePricing();

    /**
     * @param  bool
     * @return self
     */
    public function setVatInclusivePricing($vatInclusivePricing);
}
