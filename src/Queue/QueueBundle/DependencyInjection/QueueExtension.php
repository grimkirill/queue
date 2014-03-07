<?php

namespace Queue\QueueBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class QueueExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $this->loadConnections($config['connections'], $container);
        $this->loadProducers($config['producers'], $container);
    }

    protected function loadConnections($config, ContainerBuilder $container)
    {
        foreach ($config as $key => $connection) {

            $driverClass = '%grimkirill.queue.connection.driver.' . $connection['driver'] . '%';
            $definition = new Definition($driverClass, $connection);
            $container->setDefinition(sprintf('queue.connection.%s', $key), $definition);
        }
    }

    protected function loadProducers($config, ContainerBuilder $container)
    {
        foreach ($config as $key => $producer) {

            $configDefinition = new Definition('%grimkirill.queue.producer_config.class%');

            if (isset($producer['destination']) && $producer['destination']) {
                $configDefinition->addMethodCall('setDestination', [$producer['exchange']]);
            } else {
                $configDefinition->addMethodCall('setDestination', [$key]);
            }

            if (isset($producer['params']) && $producer['params']) {
                $configDefinition->addMethodCall('setParameters', [$producer['params']]);
            }

            $container->setDefinition(sprintf('queue.producer_config.%s', $key), $configDefinition);

            $serializer = new Reference(sprintf('grimkirill.queue.serializer.%s', $producer['serializer']));
            $connection = new Reference(sprintf('queue.connection.%s', $producer['connection']));
            $config = new Reference(sprintf('queue.producer_config.%s', $key));

            $producerDefinition = new Definition('%grimkirill.queue.producer.class%', [$connection, $serializer]);
            $producerDefinition->addMethodCall('setConfig', [$config]);
            $container->setDefinition(sprintf('queue.producer.%s', $key), $producerDefinition);
        }
    }
}
