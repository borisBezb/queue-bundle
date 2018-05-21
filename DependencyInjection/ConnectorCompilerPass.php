<?php

namespace Bezb\QueueBundle\DependencyInjection;

use Bezb\QueueBundle\QueueManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ConnectorCompilerPass
 * @package Bezb\QueueBundle\DependencyInjection
 */
class ConnectorCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $connectorServices = $container->findTaggedServiceIds('queue.connector');

        foreach ($connectorServices as $serviceId => $tagAttributes) {
            $container
                ->getDefinition(QueueManager::class)
                ->addMethodCall('addConnection', [$container->getDefinition($serviceId)]);
        }
    }
}