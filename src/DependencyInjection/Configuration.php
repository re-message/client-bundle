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

use RM\Bundle\ClientBundle\RmClientBundle;
use RM\Bundle\ClientBundle\Transport\TransportType;
use RM\Component\Client\Entity\User;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Oleg Kozlov <h1karo@remessage.ru>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder(RmClientBundle::NAME);
        $root = $treeBuilder->getRootNode();
        $root
            ->addDefaultsIfNotSet()
            ->children()
                ->append($this->getTransportNode())
                ->append($this->getAuthNode())
                ->append($this->getEntitiesNode())
            ->end()
        ;
        return $treeBuilder;
    }

    protected function getAuthNode(): NodeDefinition
    {
        $builder = new TreeBuilder('auth');
        $node = $builder->getRootNode();
        $node
            ->canBeDisabled()
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('auto')
                    ->info('Allow automatic authorization on each request when no token is stored')
                    ->defaultTrue()
                ->end()
                ->booleanNode('exception_on_fail')
                    ->info('Allow throwing exception on failed automatic authorization')
                    ->defaultTrue()
                ->end()
                ->scalarNode('app_id')
                    ->defaultValue('%env(string:RM_APP_ID)%')
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('app_secret')
                    ->defaultValue('%env(string:RM_APP_SECRET)%')
                    ->cannotBeEmpty()
                ->end()
            ->end();
        return $node;
    }

    protected function getTransportNode(): NodeDefinition
    {
        $builder = new TreeBuilder('transport');
        $node = $builder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->enumNode('type')
                    ->defaultValue(TransportType::HTTP)
                    ->values(TransportType::all())
                ->end()
                ->scalarNode('service')
                    ->defaultNull()
                ->end()
            ->end()
            ->beforeNormalization()
                ->ifString()
                ->then(fn(string $v) => ['type' => $v])
            ->end()
        ;
        return $node;
    }

    private function getEntitiesNode(): NodeDefinition
    {
        $builder = new TreeBuilder('entities');
        $node = $builder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('user')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('class')
                            ->defaultValue(User::class)
                            ->validate()
                                ->ifTrue(fn (string $class) => !class_exists($class))
                                ->thenInvalid('The entity class must exist.')
                            ->end()
                            ->validate()
                                ->ifTrue(fn (string $class) => !is_subclass_of($class, User::class, true))
                                ->thenInvalid('The entity class must extend the base class.')
                            ->end()
                        ->end()
                        ->booleanNode('doctrine')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
        return $node;
    }
}
