<?php

namespace Bezb\QueueBundle\Event;

use Bezb\QueueBundle\JobInterface;
use Bezb\QueueBundle\Worker\WorkerOptions;

class QueueJobEvent extends QueueWorkerEvent
{
    /**
     * @var 
     */
    protected $job;

    /**
     * QueueJobEvent constructor.
     * @param $job
     * @param $connectionName
     * @param $queueName
     * @param WorkerOptions $options
     */
    public function __construct($job, $connectionName, $queueName, WorkerOptions $options)
    {
        $this->job = $job;
        
        parent::__construct($connectionName, $queueName, $options);
    }

    /**
     * @return mixed
     */
    public function getJob()
    {
        return $this->job;
    }
}