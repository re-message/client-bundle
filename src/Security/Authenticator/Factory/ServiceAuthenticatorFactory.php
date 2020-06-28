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

namespace RM\Bundle\ClientBundle\Security\Authenticator\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use RM\Component\Client\Security\Authenticator\AuthenticatorInterface;
use RM\Component\Client\Security\Authenticator\Factory\AuthenticatorFactoryInterface;

/**
 * Class ServiceAuthenticatorFactory
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
final class ServiceAuthenticatorFactory implements AuthenticatorFactoryInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function build(string $class): AuthenticatorInterface
    {
        if (!$this->container->has($class)) {
            throw new InvalidArgumentException(sprintf('Authorization provider class `%s` does not exist.', $class));
        }

        return $this->container->get($class);
    }
}
