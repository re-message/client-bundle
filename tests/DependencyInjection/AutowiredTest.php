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

namespace RM\Bundle\ClientBundle\Tests\DependencyInjection;

use RM\Bundle\ClientBundle\Repository\ServiceRepositoryFactory;
use RM\Bundle\ClientBundle\Security\Storage\SessionAuthorizationStorage;
use RM\Bundle\ClientBundle\Tests\Stub\Autowired;
use RM\Bundle\ClientBundle\Transport\TraceableTransport;
use RM\Component\Client\Client;
use RM\Component\Client\Hydrator\EntityHydrator;
use RM\Component\Client\Security\Authenticator\Factory\AliasedAuthenticatorFactory;
use RM\Component\Client\Transport\HttpTransport;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AutowiredTest
 *
 * @author Oleg Kozlov <h1karo@outlook.com>
 */
class AutowiredTest extends WebTestCase
{
    public function testAutowiring(): void
    {
        static::bootKernel();
        $autowired = static::getContainer()->get(Autowired::class);

        static::assertInstanceOf(Client::class, $autowired->getClient());
        static::assertInstanceOf(EntityHydrator::class, $autowired->getHydrator());
        static::assertInstanceOf(TraceableTransport::class, $autowired->getTransport());
        static::assertInstanceOf(HttpTransport::class, $autowired->getTransport()->getTransport());
        static::assertInstanceOf(SessionAuthorizationStorage::class, $autowired->getAuthorizationStorage());
        static::assertInstanceOf(AliasedAuthenticatorFactory::class, $autowired->getAuthenticatorFactory());
        static::assertInstanceOf(ServiceRepositoryFactory::class, $autowired->getRepositoryFactory());
    }
}
