<?php

namespace Bezb\QueueBundle\Connector;

use Bezb\QueueBundle\Queue\QueueInterface;
use Bezb\QueueBundle\Queue\RedisQueue;
use Bezb\QueueBundle\Serializer\SerializerInterface;
use Bezb\RedisBundle\RedisManager;

/**
 * Class RedisConnector
 * @package Bezb\QueueBundle\Engine
 */
class RedisConnector implements ConnectorInterface
{
    /**
     * @var RedisManager
     */
    protected $redisManager;

    /**
     * RedisConnector constructor.
     * @param RedisManager $redisManager
     */
    public function __construct(RedisManager $redisManager)
    {
        $this->redisManager = $redisManager;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'redis';
    }

    /**
     * @param array $config
     * @param SerializerInterface $serializer
     * @return QueueInterface
     */
    public function connect(array $config, SerializerInterface $serializer): QueueInterface
    {
        $connection = $this->redisManager->getConnection($config['connection']);

        return new RedisQueue($config, $this->redisManager, $serializer);
    }

    /**
     * @param array $config
     * @return mixed|void
     */
    public function close(array $config)
    {
        $this->redisManager->close($config['connection']);
    }
}