<?php
/*
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\DataCollector;

use RM\Bundle\ClientBundle\RelmsgClientBundle;
use RM\Bundle\ClientBundle\Transport\TraceableTransport;
use RM\Component\Client\ClientInterface;
use RM\Component\Client\Entity\Application;
use RM\Component\Client\Entity\User;
use RM\Component\Client\Exception\ErrorException;
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
    private ClientInterface $client;
    private TransportInterface $transport;

    public function __construct(ClientInterface $client, TransportInterface $transport)
    {
        $this->client = $client;
        $this->transport = $transport;
    }

    public function collect(Request $request, Response $response, Throwable $exception = null): void
    {
        $this->reset();

        try {
            $application = $this->client->getApplication();
        } catch (ErrorException $exception) {
            $application = null;
        }

        try {
            $user = $this->client->getUser();
        } catch (ErrorException $exception) {
            $user = null;
        }

        $this->data['application'] = $this->cloneApplication($application);
        $this->data['user'] = $this->cloneUser($user);
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
        return $this->errorsCount() > 0;
    }

    public function errorsCount(): int
    {
        return count(
            array_filter(
                $this->getInteractions(),
                fn($messages) => $messages[1]['type'] === MessageType::ERROR
            )
        );
    }

    public function getApplication()
    {
        return $this->data['application'];
    }

    public function getUser()
    {
        return $this->data['user'];
    }

    public function getName(): string
    {
        return RelmsgClientBundle::NAME;
    }

    public function reset(): void
    {
        $this->data = [
            'application' => null,
            'user' => null,
            'interactions' => [],
        ];
    }

    private function cloneApplication(?Application $application): ?array
    {
        if (null === $application) {
            return null;
        }

        return [
            'id' => $application->getId(),
            'name' => $application->getName(),
            'initials' => $application->getInitials(),
            'photo' => null,
        ];
    }

    private function cloneUser(?User $user): ?array
    {
        if (null === $user) {
            return null;
        }

        return [
            'id' => $user->getId(),
            'name' => $user->getFullName(),
            'initials' => $user->getInitials(),
            'photo' => null,
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
