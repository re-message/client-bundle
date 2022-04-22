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

namespace RM\Bundle\ClientBundle\DependencyInjection\Compiler;

use RM\Bundle\ClientBundle\ReMessageClientBundle;
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
        foreach ($container->findTaggedServiceIds(ReMessageClientBundle::TAG_REPOSITORY) as $id => $tags) {
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
