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

namespace RM\Bundle\ClientBundle\DependencyInjection;

use Exception;
use RM\Bundle\ClientBundle\EventListener\ServiceAuthenticatorListener;
use RM\Bundle\ClientBundle\Hydrator\DoctrineHydrator;
use RM\Bundle\ClientBundle\RelmsgClientBundle;
use RM\Bundle\ClientBundle\Repository\UserRepository;
use RM\Bundle\ClientBundle\Transport\TransportType;
use RM\Component\Client\Transport\HttpTransport;
use RM\Component\Client\Transport\TransportInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use UnexpectedValueException;

/**
 * Class RelmsgClientExtension
 *
 * @author Oleg Kozlov <h1karo@relmsg.ru>
 */
class RelmsgClientExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     *
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $phpLoader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $phpLoader->load('listeners.php');

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('client.yaml');
        $loader->load('transports.yaml');
        $loader->load('hydrators.yaml');
        $loader->load('repositories.yaml');
        $loader->load('authenticators.yaml');
        $loader->load('storages.yaml');
        $loader->load('resolvers.yaml');

        if ($container->hasParameter('kernel.debug') && $container->getParameter('kernel.debug')) {
            $loader->load('debug.yaml');
        }

        $this->registerServiceAuthenticatorListener($config['auth'], $container);
        $this->registerTransport($config['transport'], $container);
        $this->registerEntities($config['entities'], $container);
    }

    private function registerServiceAuthenticatorListener(array $config, ContainerBuilder $container): void
    {
        $isAuthEnabled = $config['enabled'];
        if (!$isAuthEnabled) {
            $container
                ->getDefinition(ServiceAuthenticatorListener::class)
                ->addMethodCall('disable')
            ;

            return;
        }

        $container->setParameter(RelmsgClientBundle::APP_ID_PARAMETER, $config['app_id']);
        $container->setParameter(RelmsgClientBundle::APP_SECRET_PARAMETER, $config['app_secret']);
        $container->setParameter(RelmsgClientBundle::AUTO_AUTH_PARAMETER, $config['auto']);
        $container->setParameter(RelmsgClientBundle::ALLOW_AUTH_EXCEPTION_PARAMETER, $config['exception_on_fail']);
    }

    private function registerTransport(array $config, ContainerBuilder $container): void
    {
        if ($config['service'] === null) {
            $type = new TransportType($config['type']);
            $class = $this->getTransportClass($type);
        } else {
            $class = $config['service'];
        }

        $this->registerOrAlias($container, TransportInterface::class, $class);
    }

    protected function getTransportClass(TransportType $type): string
    {
        if ($type->is(TransportType::HTTP)) {
            return HttpTransport::class;
        }

        throw new UnexpectedValueException(sprintf('Transport %s is not supported.', $type->getName()));
    }

    private function registerOrAlias(ContainerBuilder $container, string $alias, string $class): void
    {
        if ($container->has($class)) {
            $container->setAlias($alias, $class);
        } else {
            $container->register($alias, $class);
        }
    }

    private function registerEntities(array $config, ContainerBuilder $container): void
    {
        $container
            ->getDefinition(DoctrineHydrator::class)
            ->setArgument(
                '$entities',
                array_combine(
                    array_map(fn (array $entity) => $entity['class'], $config),
                    array_map(fn (array $entity) => $entity['doctrine'], $config)
                )
            )
        ;

        $userClass = $config['user']['class'];
        $container
            ->getDefinition(UserRepository::class)
            ->setArgument('$class', $userClass)
        ;
    }
}
