<?php
/*
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2022 Relations Messenger
 * @author    Oleg Kozlov <h1karo@relmsg.ru>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Security\Storage;

use LogicException;
use RM\Component\Client\Exception\TokenNotFoundException;
use RM\Component\Client\Security\Credentials\AuthorizationInterface;
use RM\Component\Client\Security\Storage\AuthorizationStorageInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionAuthorizationStorage implements AuthorizationStorageInterface
{
    private const SESSION_NAMESPACE = '_relmsg';

    private RequestStack $requestStack;
    private string $namespace;

    public function __construct(RequestStack $requestStack, string $namespace = self::SESSION_NAMESPACE)
    {
        $this->requestStack = $requestStack;
        $this->namespace = $namespace;
    }

    public function set(string $type, AuthorizationInterface $auth): void
    {
        if (!$this->getSession()->isStarted()) {
            $this->getSession()->start();
        }

        $this->getSession()->set($this->getName($type), $auth);
    }

    public function get(string $type): AuthorizationInterface
    {
        if (!$this->has($type)) {
            throw new TokenNotFoundException($type);
        }

        return $this->getSession()->get($this->getName($type));
    }

    public function has(string $type): bool
    {
        if (!$this->getSession()->isStarted()) {
            $this->getSession()->start();
        }

        return $this->getSession()->has($this->getName($type));
    }

    protected function getName(string $type): string
    {
        return "{$this->namespace}/{$type}";
    }

    private function getSession(): SessionInterface
    {
        if (method_exists($this->requestStack, 'getSession')) {
            return $this->requestStack->getSession();
        }

        $request = $this->requestStack->getCurrentRequest();
        if (!$request->hasSession()) {
            throw new LogicException('There is currently no session available.');
        }

        return $request->getSession();
    }
}
