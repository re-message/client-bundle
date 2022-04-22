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

namespace RM\Bundle\ClientBundle\Security\Authenticator\Factory;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use RM\Component\Client\Security\Authenticator\AuthenticatorInterface;
use RM\Component\Client\Security\Authenticator\Factory\AuthenticatorFactoryInterface;

/**
 * Class ServiceAuthenticatorFactory
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
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
