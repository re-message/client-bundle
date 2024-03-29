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

namespace RM\Bundle\ClientBundle\Tests\DependencyInjection;

use RM\Bundle\ClientBundle\Repository\ServiceRepositoryFactory;
use RM\Bundle\ClientBundle\Security\Storage\SessionAuthorizationStorage;
use RM\Bundle\ClientBundle\Tests\Stub\Autowired;
use RM\Bundle\ClientBundle\Transport\TraceableTransport;
use RM\Component\Client\Client;
use RM\Component\Client\Hydrator\EntityHydrator;
use RM\Component\Client\Hydrator\EventfulHydrator;
use RM\Component\Client\Security\Authenticator\Factory\AliasedAuthenticatorFactory;
use RM\Component\Client\Transport\HttpTransport;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AutowiredTest
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class AutowiredTest extends WebTestCase
{
    public function testAutowiring(): void
    {
        static::bootKernel();
        $autowired = static::getContainer()->get(Autowired::class);

        static::assertInstanceOf(Client::class, $autowired->getClient());

        $hydrator = $autowired->getHydrator();
        static::assertInstanceOf(EventfulHydrator::class, $hydrator);
        static::assertInstanceOf(EntityHydrator::class, $hydrator->getHydrator());

        $transport = $autowired->getTransport();
        static::assertInstanceOf(TraceableTransport::class, $transport);
        static::assertInstanceOf(HttpTransport::class, $transport->getTransport());
        static::assertInstanceOf(SessionAuthorizationStorage::class, $autowired->getAuthorizationStorage());
        static::assertInstanceOf(AliasedAuthenticatorFactory::class, $autowired->getAuthenticatorFactory());
        static::assertInstanceOf(ServiceRepositoryFactory::class, $autowired->getRepositoryFactory());
    }
}
