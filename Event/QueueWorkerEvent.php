<?php

namespace Bezb\QueueBundle\Event;

use Bezb\QueueBundle\Worker\WorkerOptions;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class QueueWorkerEvent
 * @package Bezb\QueueBundle\Event
 */
class QueueWorkerEvent extends Event
{
    /**
     * @var string
     */
    protected $connectionName;

    /**
     * @var string
     */
    protected $queueName;

    /**
     * @var WorkerOptions 
     */
    protected $options;
    
    public function __construct($connectionName, $queueName, WorkerOptions $options)
    {
        $this->connectionName   = $connectionName;
        $this->queueName        = $queueName;
        $this->options          = $options;
    }

    /**
     * @return string
     */
    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    /**
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->queueName;
    }

    /**
     * @return WorkerOptions
     */
    public function getOptions(): WorkerOptions
    {
        return $this->options;
    }
}