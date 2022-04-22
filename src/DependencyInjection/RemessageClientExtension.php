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

namespace RM\Bundle\ClientBundle\DependencyInjection;

use Exception;
use RM\Bundle\ClientBundle\Entity\EntityRegistry;
use RM\Bundle\ClientBundle\EventListener\HydrationListener;
use RM\Bundle\ClientBundle\EventListener\ServiceAuthenticatorListener;
use RM\Bundle\ClientBundle\RemessageClientBundle;
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
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class RemessageClientExtension extends Extension
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $phpLoader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $phpLoader->load('entities.php');
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

        $container->setParameter(RemessageClientBundle::APP_ID_PARAMETER, $config['app_id']);
        $container->setParameter(RemessageClientBundle::APP_SECRET_PARAMETER, $config['app_secret']);
        $container->setParameter(RemessageClientBundle::AUTO_AUTH_PARAMETER, $config['auto']);
        $container->setParameter(RemessageClientBundle::ALLOW_AUTH_EXCEPTION_PARAMETER, $config['exception_on_fail']);
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
        $bundles = $container->getParameter('kernel.bundles');
        $hasDoctrineBundle = array_key_exists('DoctrineBundle', $bundles);
        $hasDoctrineEntities = $this->containsDoctrineEntities($config);

        if ($hasDoctrineEntities && !$hasDoctrineBundle) {
            throw new UnexpectedValueException('Doctrine entities usage requires Doctrine Bundle.');
        }

        if ($hasDoctrineBundle) {
            $container->register(HydrationListener::class);
        }

        $container
            ->getDefinition(EntityRegistry::class)
            ->setArgument(0, $config)
        ;

        $userClass = $config['user']['class'];
        $container
            ->getDefinition(UserRepository::class)
            ->setArgument('$class', $userClass)
        ;
    }

    private function containsDoctrineEntities(array $config): bool
    {
        return array_reduce(
            $config,
            fn (bool $carry, array $entity) => $carry || $entity['doctrine'],
            false
        );
    }
}
