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
    protected $redisManager;

    /**
     * @var string
     */
    protected $default;

    /**
     * @var mixed
     */
    protected $connectionName;

    /**
     * RedisQueue constructor.
     * @param array $config
     * @param RedisManager $redisManager
     * @param SerializerInterface $serializer
     */
    public function __construct(array $config, RedisManager $redisManager, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->redisManager = $redisManager;

        $this->default = $config['default_queue'];
        $this->connectionName = $config['connection'];
    }

    /**
     * @param JobInterface $job
     * @param null $data
     * @param null|string $queue
     * @return mixed|void
     */
    public function push(JobInterface $job, $data = null, ?string $queue = null)
    {
        $this->getClient()->rPush($queue ?: $this->default, $this->serializer->serialize($job));
    }

    /**
     * @param null|string $queue
     * @return JobInterface
     */
    public function pop(?string $queue = null): ?JobInterface
    {
        $rawJob = $this->getClient()->lPop($queue ?: $this->default);

        if (!$rawJob) {
            return null;
        }

        return $this->serializer->unserialize($rawJob);
    }

    /**
     * @return Connection
     */
    protected function getClient(): Connection
    {
        return $this->redisManager->getConnection($this->connectionName);
    }
}