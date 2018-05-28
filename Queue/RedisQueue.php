<?php

namespace Bezb\QueueBundle\Queue;

use Bezb\QueueBundle\JobInterface;
use Bezb\QueueBundle\Serializer\SerializerInterface;
use Bezb\RedisBundle\Connection\Connection;
use Bezb\RedisBundle\RedisManager;

/**
 * Class RedisQueue
 * @package Bezb\QueueBundle\Queue
 */
class RedisQueue implements QueueInterface
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $default;

    /**
     * RedisQueue constructor.
     * @param string $default
     * @param Connection $connection
     * @param SerializerInterface $serializer
     */
    public function __construct(string $default, Connection $connection, SerializerInterface $serializer)
    {
        $this->default = $default;
        $this->serializer = $serializer;
        $this->connection = $connection;
    }

    /**
     * @param JobInterface $job
     * @param null $data
     * @param null|string $queue
     * @return mixed|void
     */
    public function push(JobInterface $job, $data = null, ?string $queue = null)
    {
        $this->connection->rPush($queue ?: $this->default, $this->serializer->serialize($job));
    }

    /**
     * @param null|string $queue
     * @return JobInterface
     */
    public function pop(?string $queue = null): ?JobInterface
    {
        $rawJob = $this->connection->lPop($queue ?: $this->default);

        if (!$rawJob) {
            return null;
        }

        try {
            $job = $this->serializer->unserialize($rawJob);
        } catch (\Exception $e) {
            return null;
        }

        return $job;
    }

    public function closeConnection()
    {
        $this->connection->close();
    }
}