<?php

namespace Sitetheory\Bundle\ProfilerStorageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sitetheory_profiler_storage');

        $rootNode
            ->children()
                ->arrayNode('profiler')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('defaultStorage')
                            ->defaultTrue()
                    ->end()
                    ->scalarNode('class')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('dsn')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('username')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('password')
                        ->defaultValue('')
                    ->end()
                    ->scalarNode('ttl')
                        ->defaultValue('3600')
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
