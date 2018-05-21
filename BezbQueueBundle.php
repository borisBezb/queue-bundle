<?php

namespace Bezb\QueueBundle;

use Bezb\QueueBundle\Connector\ConnectorInterface;
use Bezb\QueueBundle\DependencyInjection\ConnectorCompilerPass;
use Bezb\QueueBundle\DependencyInjection\JobHandlerCompilerPass;
use Bezb\QueueBundle\Worker\ConnectionKeeper\ConnectionKeeperInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class BorisbezbQueueBundle
 * @package Borisbezb\QueueBundle
 */
class BezbQueueBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container); // TODO: Change the autogenerated stub

        $container
            ->registerForAutoconfiguration(ConnectorInterface::class)
            ->addTag('queue.connector')
        ;

        $container
            ->registerForAutoconfiguration(ConnectionKeeperInterface::class)
            ->addTag('queue.worker.connection_keeper')
        ;

        $container
            ->registerForAutoconfiguration(JobHandlerInterface::class)
            ->setPublic(true)
        ;

        $container->addCompilerPass(new ConnectorCompilerPass());
    }
}