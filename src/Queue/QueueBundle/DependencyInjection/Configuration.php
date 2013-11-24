<?php

namespace Queue\QueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('queue');
        $rootNode
            ->children()
                ->arrayNode('connection')
                    ->children()
                        ->scalarNode('driver')
                            ->isRequired()
                            ->validate()
                            ->ifNotInArray(array('stomp', 'sqlite', 'mssql'))
                                ->thenInvalid('Invalid  driver "%s"')
                            ->end()
                        ->end()
                        ->variableNode('host')->end()
                        ->variableNode('config')->end()
                    ->end()

                ->end();
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
