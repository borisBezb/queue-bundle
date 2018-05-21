<?php

namespace Bezb\QueueBundle;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class QueueHandler
 * @package Bezb\QueueBundle
 */
class QueueHandler
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * QueueHandler constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param JobInterface $job
     */
    public function handle(JobInterface $job)
    {
        $this->container->get($job->getHandler())->handle($job);
    }
}