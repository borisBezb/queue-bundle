<?php

namespace Bezb\QueueBundle\Queue;

use Bezb\QueueBundle\JobInterface;
use Bezb\QueueBundle\Serializer\SerializerInterface;
use Bezb\RedisBundle\Connection\Connection;

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
    protected $client;

    /**
     * @var string
     */
    protected $default;

    /**
     * RedisQueue constructor.
     * @param Connection $client
     * @param string $default
     * @param SerializerInterface $serializer
     */
    public function __construct(Connection $client, string $default, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->client = $client;
        $this->default = $default;
    }

    /**
     * @param JobInterface $job
     * @param null $data
     * @param null|string $queue
     * @return mixed|void
     */
    public function push(JobInterface $job, $data = null, ?string $queue)
    {
        $this->client->rPush($queue ?: $this->default, $this->serializer->serialize($job));
    }

    /**
     * @param null|string $queue
     * @return JobInterface
     */
    public function pop(?string $queue): JobInterface
    {
        $rawJob = $this->client->lPop($queue ?: $this->default);

        return $this->serializer->unserialize($rawJob);
    }
}