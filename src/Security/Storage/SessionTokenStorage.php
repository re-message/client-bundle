<?php
/**
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    h1karo <h1karo@outlook.com>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Security\Storage;

use RM\Component\Client\Exception\TokenNotFoundException;
use RM\Component\Client\Security\Storage\TokenStorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class SessionTokenStorage
 *
 * @package RM\Bundle\ClientBundle\Security\Storage
 * @author  h1karo <h1karo@outlook.com>
 */
class SessionTokenStorage implements TokenStorageInterface
{
    private const SESSION_NAMESPACE = '_relmsg';

    private SessionInterface $session;
    private string $namespace;

    public function __construct(SessionInterface $session, string $namespace = self::SESSION_NAMESPACE)
    {
        $this->session = $session;
        $this->namespace = $namespace;
    }

    public function set(string $type, string $token): void
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        $this->session->set($this->getName($type), $token);
    }

    public function get(string $type): string
    {
        if (!$this->has($type)) {
            throw new TokenNotFoundException($type);
        }

        return $this->session->get($this->getName($type));
    }

    public function has(string $type): bool
    {
        if (!$this->session->isStarted()) {
            $this->session->start();
        }

        return $this->session->has($this->getName($type));
    }

    protected function getName(string $type): string
    {
        return "{$this->namespace}/{$type}";
    }
}
