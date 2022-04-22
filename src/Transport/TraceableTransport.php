<?php
/*
 * This file is a part of Re Message Client Bundle.
 * This package is a part of Re Message.
 *
 * @link      https://github.com/re-message/client-bundle
 * @link      https://dev.remessage.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2022 Re Message
 * @author    Oleg Kozlov <h1karo@remessage.ru>
 * @license   Apache License 2.0
 * @license   https://legal.remessage.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Transport;

use Psr\Log\LoggerInterface;
use RM\Component\Client\Exception\ErrorException;
use RM\Component\Client\Transport\DecoratedTransport;
use RM\Component\Client\Transport\TransportInterface;
use RM\Standard\Message\MessageInterface;
use Symfony\Contracts\Service\ResetInterface;

/**
 * Class TraceableTransport decorates to log and analysis on message sending.
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class TraceableTransport extends DecoratedTransport implements ResetInterface
{
    private LoggerInterface $logger;

    /**
     * @var array<MessageInterface>[]
     */
    private iterable $interactions = [];

    public function __construct(TransportInterface $transport, LoggerInterface $logger)
    {
        parent::__construct($transport);
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     * @throws ErrorException
     */
    public function send(MessageInterface $sent): MessageInterface
    {
        try {
            $received = parent::send($sent);
            $this->log($sent, $received);
        } catch (ErrorException $e) {
            $this->log($sent, $e->getError());
            throw $e;
        }

        return $received;
    }

    private function log(MessageInterface $sent, MessageInterface $received): void
    {
        $transport = $this->getTransport();
        $message = sprintf('%s sent message to core.', get_class($transport));
        $this->logger->debug($message, ['sent' => $sent->toArray(), 'received' => $received->toArray()]);
        $this->interactions[] = [$sent, $received];
    }

    public function reset(): void
    {
        $this->interactions = [];
    }

    /**
     * @return MessageInterface[][]
     */
    public function getInteractions(): array
    {
        return $this->interactions;
    }
}
