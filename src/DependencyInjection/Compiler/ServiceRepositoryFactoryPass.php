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

namespace RM\Bundle\ClientBundle\DependencyInjection\Compiler;

use RM\Bundle\ClientBundle\RelmsgClientBundle;
use RM\Bundle\ClientBundle\Repository\ServiceRepositoryFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ServiceRepositoryFactoryPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     * @see ServiceRepositoryFactory::setRepository()
     */
    public function process(ContainerBuilder $container): void
    {
        $factoryDefinition = $container->getDefinition(ServiceRepositoryFactory::class);
        foreach ($container->findTaggedServiceIds(RelmsgClientBundle::TAG_REPOSITORY) as $id => $tags) {
            $reference = new Reference($id);

            foreach ($tags as $tag) {
                $name = $tag['alias'] ?? $id;
                $factoryDefinition->addMethodCall('setRepository', [$reference, $name]);

                if (array_key_exists('alias', $tag)) {
                    $alias = $tag['alias'];
                    $container->setAlias($alias, $id);
                }
            }
        }
    }
}
