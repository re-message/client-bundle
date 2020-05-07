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

namespace RM\Bundle\ClientBundle\Tests\DependencyInjection;

use RM\Bundle\ClientBundle\Repository\ServiceRepositoryFactory;
use RM\Bundle\ClientBundle\Security\Authenticator\ServiceAuthenticatorFactory;
use RM\Bundle\ClientBundle\Security\Storage\SessionTokenStorage;
use RM\Bundle\ClientBundle\Tests\Stub\Autowired;
use RM\Bundle\ClientBundle\Transport\TraceableTransport;
use RM\Component\Client\Client;
use RM\Component\Client\Hydrator\EntityHydrator;
use RM\Component\Client\Transport\HttpTransport;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class AutowiredTest
 *
 * @package RM\Bundle\ClientBundle\Tests\DependencyInjection
 * @author  h1karo <h1karo@outlook.com>
 */
class AutowiredTest extends WebTestCase
{
    public function testAutowiring(): void
    {
        self::bootKernel();
        $autowired = self::$container->get(Autowired::class);

        $this->assertInstanceOf(Client::class, $autowired->getClient());
        $this->assertInstanceOf(EntityHydrator::class, $autowired->getHydrator());
        $this->assertInstanceOf(TraceableTransport::class, $autowired->getTransport());
        $this->assertInstanceOf(HttpTransport::class, $autowired->getTransport()->getRealTransport());
        $this->assertInstanceOf(SessionTokenStorage::class, $autowired->getTokenStorage());
        $this->assertInstanceOf(ServiceAuthenticatorFactory::class, $autowired->getAuthenticatorFactory());
        $this->assertInstanceOf(ServiceRepositoryFactory::class, $autowired->getRepositoryFactory());
    }
}
