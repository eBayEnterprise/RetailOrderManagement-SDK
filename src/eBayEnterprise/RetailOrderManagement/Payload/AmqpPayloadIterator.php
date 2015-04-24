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

use eBayEnterprise\RetailOrderManagement\Api\IAmqpApi;
use OutOfBoundsException;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Class AmqpPayloadIterator
 * @package eBayEnterprise\RetailOrderManagement\Payload\Iterator
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AmqpPayloadIterator implements IPayloadIterator
{
    /** @var IAmqpApi */
    protected $api;
    /** @var IMessageFactory */
    protected $messageFactory;
    /** @var int */
    protected $maxMessages;
    /** @var int */
    protected $currentKey = 0;
    /** @var AMQPMessage[] */
    protected $messages = [];
    /** @var IPayload[] */
    protected $payloads = [];
    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param IAmqpApi $api AMQP Api instance used to get messages from a queue
     * @param IMessageFactory $messageFactory Maps the message type to a payload for each message received
     * @param int $maxMessages Max messages to retrieve from the queue
     * @param LoggerInterface $logger
     */
    public function __construct(IAmqpApi $api, IMessageFactory $messageFactory, $maxMessages, LoggerInterface $logger = null)
    {
        $this->logger = $logger ?: new NullLogger();
        $this->api = $api;
        $this->messageFactory = $messageFactory;
        $this->maxMessages = $maxMessages;
    }

    /**
     * get the current payload in the iterable
     * @return IPayload|null Returns null when attempting to get the current item from an invalid iterator.
     * @throws Exception\InvalidPayload If message cannot be successfully deserialized into a payload.
     * @throws Exception\UnexpectedResponse If message does not specify the type of message.
     * @throws Exception\UnsupportedPayload If current message does not match a known payload type.
     */
    public function current()
    {
        // if the iterator is no longer valid, return null
        if (!$this->valid()) {
            return null;
        }
        if (!isset($this->payloads[$this->currentKey])) {
            $this->payloads[$this->currentKey] = $this->processMessage($this->messages[$this->currentKey]);
        }
        return $this->payloads[$this->currentKey];
    }

    /**
     * Test if there is a payload at the current index. Called after self::rewind
     * and self::next while iterating over the messages.
     * @return bool
     */
    public function valid()
    {
        // don't process more than the max messages
        if ($this->currentKey >= $this->maxMessages) {
            return false;
        }
        // if a payload already exists for the current key, we'll be able to return that
        if (isset($this->messages[$this->currentKey])) {
            return true;
        }
        // if no payload exists for the current key yet, try to get the next one
        $message = $this->api->getNextMessage();
        if ($message) {
            $this->messages[$this->currentKey] = $message;
            return true;
        }
        // if no message exists and no new messages could be received,
        // end the iterable
        return false;
    }

    /**
     * Get the appropriate payload type for the AMQP message and deserialize
     * the message body with the payload.
     * @param AMQPMessage $message
     * @throws Exception\UnexpectedResponse
     * @return IPayload
     */
    protected function processMessage(AMQPMessage $message)
    {
        try {
            $type = $message->get('type');
        } catch (OutOfBoundsException $e) {
            throw new Exception\UnexpectedResponse('No "type" set for message.');
        }
        $payload = $this->messageFactory->messagePayload($type);
        $payload->deserialize($message->body);
        return $payload;
    }

    /**
     * get the key for the current payload in the iterable
     * @return int|float|string|bool
     */
    public function key()
    {
        return $this->currentKey;
    }

    /**
     * Advance the current index of the iterable.
     * Gets the next payload from the queue and process it - deserialize
     * into a payload.
     * @return void
     */
    public function next()
    {
        $this->currentKey++;
    }

    /**
     * Set the current index back to 0, should replay received messages
     * after being rewound.
     * @return void
     */
    public function rewind()
    {
        $this->currentKey = 0;
    }
}
