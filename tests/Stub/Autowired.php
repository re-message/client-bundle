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
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
