<?php

namespace RM\Bundle\ClientBundle\DataCollector;

use RM\Bundle\ClientBundle\RelmsgClientBundle;
use RM\Bundle\ClientBundle\Transport\TraceableTransport;
use RM\Component\Client\Transport\TransportInterface;
use RM\Standard\Message\MessageInterface;
use RM\Standard\Message\MessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpKernel\DataCollector\LateDataCollectorInterface;
use Throwable;
use Traversable;

class ClientDataCollector extends DataCollector implements LateDataCollectorInterface
{
    private TransportInterface $transport;

    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function collect(Request $request, Response $response, Throwable $exception = null): void
    {
        $this->reset();
    }

    public function lateCollect(): void
    {
        if ($this->transport instanceof TraceableTransport) {
            $iterator = $this->collectFromTransport($this->transport);
            $this->data['interactions'] = $iterator instanceof Traversable ? iterator_to_array($iterator) : $iterator;
        }
    }

    public function isEnabled(): bool
    {
        return true;
    }

    public function getInteractions(): array
    {
        return $this->data['interactions'];
    }

    public function hasErrors(): bool
    {
        foreach ($this->getInteractions() as [, $received]) {
            if ($received['type'] === MessageType::ERROR) {
                return true;
            }
        }

        return false;
    }

    public function getName(): string
    {
        return RelmsgClientBundle::NAME;
    }

    public function reset(): void
    {
        $this->data = [
            'interactions' => [],
        ];
    }

    private function collectFromTransport(TraceableTransport $transport): iterable
    {
        /**
         * @var MessageInterface $sent
         * @var MessageInterface $received
         */
        foreach ($transport->getInteractions() as [$sent, $received]) {
            yield [$this->collectFromMessage($sent), $this->collectFromMessage($received)];
        }
    }

    private function collectFromMessage(MessageInterface $message): array
    {
        return [
            'type' => $message->getType(),
            'content' => $this->cloneVar($message->toArray()),
        ];
    }
}
