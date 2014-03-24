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
        $this->loadConsumers($config['consumers'], $container);
    }

    /**
     * соединения
     *
     * @param $config
     * @param ContainerBuilder $container
     */
    protected function loadConnections($config, ContainerBuilder $container)
    {
        foreach ($config as $key => $connection) {
            $driverClass = '%grimkirill.queue.connection.driver.' . $connection['driver'] . '%';
            $definition = new Definition($driverClass, [$connection]);
            if ($connection['driver'] == 'direct') {
                $definition->addMethodCall('setContainer', [new Reference('service_container')]);
            }
            $container->setDefinition(sprintf('queue.connection.%s', $key), $definition);
        }
    }

    /**
     * Постановщики задач
     *
     * @param $config
     * @param ContainerBuilder $container
     */
    protected function loadProducers($config, ContainerBuilder $container)
    {
        foreach ($config as $key => $producer) {

            $configDefinition = new Definition('%grimkirill.queue.producer_config.class%');

            if (isset($producer['exchange']) && $producer['exchange']) {
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

    /**
     * Обработчики задач
     *
     * @param $config
     * @param ContainerBuilder $container
     */
    protected function loadConsumers($config, ContainerBuilder $container)
    {
        foreach ($config as $key => $consumer) {
            $consumerDefinition = new Definition('%grimkirill.queue.consumer.class%');
            $serializer = new Reference(sprintf('grimkirill.queue.serializer.%s', $consumer['serializer']));
            $connection = new Reference(sprintf('queue.connection.%s', $consumer['connection']));
            $consumerDefinition->addMethodCall('setSerializer', [$serializer]);
            $consumerDefinition->addMethodCall('setDriver', [$connection]);
            $callback = $consumer['callback'];
            if (is_array($callback)) {
                $consumerDefinition->addMethodCall('setCallback', [[
                    new Reference(array_shift($callback)), array_shift($callback)
                ]]);
            } else {
                $consumerDefinition->addMethodCall('setCallback', [[
                    new Reference($callback), 'execute'
                ]]);
            }

            $configDefinition = new Definition('%grimkirill.queue.consumer_config.class%');

            if (isset($producer['exchange']) && $producer['exchange']) {
                $configDefinition->addMethodCall('setDestination', [$producer['exchange']]);
            } else {
                $configDefinition->addMethodCall('setDestination', [$key]);
            }

            if (isset($producer['params']) && $producer['params']) {
                $configDefinition->addMethodCall('setParameters', [$producer['params']]);
            }

            $container->setDefinition(sprintf('queue.consumer_config.%s', $key), $configDefinition);

            $config = new Reference(sprintf('queue.consumer_config.%s', $key));
            $consumerDefinition->addMethodCall('setConfig', [$config]);

            $consumerId = sprintf('queue.consumer.%s', $key);
            $container->setDefinition($consumerId, $consumerDefinition);
            $holder = $container->getDefinition('grimkirill.queue.holder');
            $holder->addMethodCall('addConsumer', [$consumerId]);
            $holder->addMethodCall('addConsumerCount', [$consumerId, $consumer['number']]);
        }
    }
}
