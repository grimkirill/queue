<?php

namespace Queue\QueueBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
                ->arrayNode('connections')
                    ->canBeUnset()
                    ->useAttributeAsKey('key')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('driver')
                                ->defaultValue('ampq')
                                ->isRequired()->validate()
                                    ->ifNotInArray(array('stomp', 'stomp_apollo', 'ampq', 'array', 'service'))
                                    ->thenInvalid('Invalid  driver "%s"')
                                ->end()
                            ->end()
                            ->scalarNode('host')->defaultValue('localhost')->end()
                            ->scalarNode('port')->defaultValue(null)->end()
                            ->scalarNode('user')->defaultValue('guest')->end()
                            ->scalarNode('password')->defaultValue('guest')->end()
                            ->scalarNode('vhost')->defaultValue('/')->end()
                            ->booleanNode('lazy')->defaultFalse()->end()
                        ->end()
                    ->end()
                ->end()

                ->arrayNode('producers')
                    ->canBeUnset()
                    ->useAttributeAsKey('key')
                    ->prototype('array')
//                    ->append($this->getExchangeConfiguration())
//                    //->append($this->getQueueConfiguration())
//            //            ->children()
//            //                ->scalarNode('connection')->defaultValue('default')->end()
//            //                ->scalarNode('auto_setup_fabric')->defaultTrue()->end()
//            //                ->scalarNode('class')->defaultValue('%old_sound_rabbit_mq.producer.class%')->end()
//            //            ->end()
                    ->end()
                ->end()
            ->end();


        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }

    protected function getExchangeConfiguration()
    {
        $node = new ArrayNodeDefinition('exchange_options');
        return $node
            ->children()
                ->scalarNode('name')->end()
                ->scalarNode('type')->end()
                ->booleanNode('passive')->defaultValue(false)->end()
                ->booleanNode('durable')->defaultValue(true)->end()
                ->booleanNode('auto_delete')->defaultValue(false)->end()
                ->booleanNode('internal')->defaultValue(false)->end()
                ->booleanNode('nowait')->defaultValue(false)->end()
                ->variableNode('arguments')->defaultNull()->end()
                ->scalarNode('ticket')->defaultNull()->end()
            ->end()
            ;
    }

}
