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

namespace RM\Bundle\ClientBundle\Security;

use InvalidArgumentException;
use Psr\Container\ContainerInterface;
use RM\Component\Client\Security\AuthenticatorFactory;
use RM\Component\Client\Security\AuthenticatorFactoryInterface;
use RM\Component\Client\Security\AuthenticatorInterface;

/**
 * Class ServiceAuthenticatorFactory
 *
 * @package RM\Bundle\ClientBundle\Security
 * @author  h1karo <h1karo@outlook.com>
 */
final class ServiceAuthenticatorFactory implements AuthenticatorFactoryInterface
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function build(string $type): AuthenticatorInterface
    {
        $class = AuthenticatorFactory::PROVIDERS[$type] ?? null;
        if ($class === null || !$this->container->has($class)) {
            throw new InvalidArgumentException(sprintf('Authorization provider with name `%s` does not exist.', $type));
        }

        return $this->container->get($class);
    }
}
