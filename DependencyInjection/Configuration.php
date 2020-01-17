<?php

namespace Bezb\QueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Bezb\QueueBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('bezb_queue');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('default_connection')
                    ->isRequired()
                ->end()
                ->arrayNode('connections')
                    ->children()
                        ->arrayNode('redis')
                            ->useAttributeAsKey('name')
                            ->arrayPrototype()
                                ->children()
                                    ->scalarNode('connection')->end()
                                    ->scalarNode('default_queue')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}