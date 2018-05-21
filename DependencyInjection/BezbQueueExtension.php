<?php

namespace Bezb\QueueBundle\DependencyInjection;

use Bezb\QueueBundle\QueueManager;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class BezbQueueExtension
 * @package Bezb\QueueBundle\DependencyInjection
 */
class BezbQueueExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $manager = $container->getDefinition(QueueManager::class);
        $manager
            ->addArgument($config['default'])
            ->addArgument($config['connections'])
        ;
    }
}