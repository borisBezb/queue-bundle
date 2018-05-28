<?php

namespace Bezb\QueueBundle\Queue;
use Bezb\QueueBundle\JobInterface;

/**
 * Interface QueueInterface
 * @package Bezb\QueueBundle\Queue
 */
interface QueueInterface
{
    /**
     * @param $job
     * @param null $data
     * @param null|string $queue
     * @return mixed
     */
    public function push(JobInterface $job, $data = null, ?string $queue = null);

    /**
     * @param null|string $queue
     * @return mixed
     */
    public function pop(?string $queue = null): ?JobInterface;

    /**
     * @return mixed
     */
    public function closeConnection();
}