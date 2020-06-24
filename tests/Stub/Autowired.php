<?php
/**
 * This file is a part of Relations Messenger Client Bundle.
 * This package is a part of Relations Messenger.
 *
 * @link      https://github.com/relmsg/client-bundle
 * @link      https://dev.relmsg.ru/packages/client-bundle
 * @copyright Copyright (c) 2018-2020 Relations Messenger
 * @author    Oleg Kozlov <h1karo@outlook.com>
 * @license   https://legal.relmsg.ru/licenses/client-bundle
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace RM\Bundle\ClientBundle\Tests\Stub;

use RM\Component\Client\ClientInterface;
use RM\Component\Client\Hydrator\HydratorInterface;
use RM\Component\Client\Repository\Factory\RepositoryFactoryInterface;
use RM\Component\Client\Repository\Registry\RepositoryRegistryInterface;
use RM\Component\Client\Security\Authenticator\Factory\AuthenticatorFactoryInterface;
use RM\Component\Client\Security\Storage\AuthorizationStorageInterface;
use RM\Component\Client\Transport\TransportInterface;

/**
 * Class Autowired
 *
 * @package RM\Bundle\ClientBundle\Tests\Stub
 * @author  h1karo <h1karo@outlook.com>
 */
class Autowired
{
    private ClientInterface $client;
    private HydratorInterface $hydrator;
    private RepositoryRegistryInterface $registry;
    private RepositoryFactoryInterface $repositoryFactory;
    private AuthorizationStorageInterface $authorizationStorage;
    private AuthenticatorFactoryInterface $authenticatorFactory;
    private TransportInterface $transport;

    public function __construct(
        ClientInterface $client,
        HydratorInterface $hydrator,
        RepositoryRegistryInterface $registry,
        RepositoryFactoryInterface $factory,
        AuthorizationStorageInterface $authorizationStorage,
        AuthenticatorFactoryInterface $authenticatorFactory,
        TransportInterface $transport
    ) {
        $this->client = $client;
        $this->hydrator = $hydrator;
        $this->registry = $registry;
        $this->repositoryFactory = $factory;
        $this->authorizationStorage = $authorizationStorage;
        $this->authenticatorFactory = $authenticatorFactory;
        $this->transport = $transport;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    public function getHydrator(): HydratorInterface
    {
        return $this->hydrator;
    }

    public function getRegistry(): RepositoryRegistryInterface
    {
        return $this->registry;
    }

    public function getRepositoryFactory(): RepositoryFactoryInterface
    {
        return $this->repositoryFactory;
    }

    public function getAuthorizationStorage(): AuthorizationStorageInterface
    {
        return $this->authorizationStorage;
    }

    public function getAuthenticatorFactory(): AuthenticatorFactoryInterface
    {
        return $this->authenticatorFactory;
    }

    public function getTransport(): TransportInterface
    {
        return $this->transport;
    }
}
