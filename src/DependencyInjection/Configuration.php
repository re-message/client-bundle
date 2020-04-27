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

namespace RM\Bundle\ClientBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @package RM\Bundle\ClientBundle\DependencyInjection
 * @author  h1karo <h1karo@outlook.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('relmsg_client');
        $root = $treeBuilder->getRootNode();
        $root
            ->addDefaultsIfNotSet()
            ->children()
                ->append($this->getAuthNode())
            ->end()
        ;
        return $treeBuilder;
    }

    protected function getAuthNode(): NodeDefinition
    {
        $builder = new TreeBuilder('auth');
        $node = $builder->getRootNode();
        $node
            ->addDefaultsIfNotSet()
            ->children()
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
}
