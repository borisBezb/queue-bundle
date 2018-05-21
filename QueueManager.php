<?php

namespace Bezb\QueueBundle;

use Bezb\QueueBundle\Connector\ConnectorInterface;
use Bezb\QueueBundle\Queue\QueueInterface;
use Bezb\QueueBundle\Serializer\SerializerInterface;

/**
 * Class QueueManager
 * @package Bezb\QueueBundle
 */
class QueueManager
{
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $connections = [];

    /**
     * @var ConnectorInterface[]
     */
    protected $connectors = [];

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $default;

    /**
     * QueueManager constructor.
     * @param SerializerInterface $serializer
     * @param string $default
     * @param array $config
     */
    public function __construct(SerializerInterface $serializer, string $default, array $config)
    {
        $this->serializer = $serializer;
        $this->default = $default;
        $this->config = $config;
    }

    /**
     * @param null|string $connection
     * @return QueueInterface
     * @throws \Exception
     */
    public function getConnection(?string $connection): QueueInterface
    {
        if (!$connection) {
            $connection = $this->default;
        }

        if (!isset($this->connections[$connection])) {
            $this->connections[$connection] = $this->resolveConnection($connection);
        }

        return $this->connections[$connection];
    }

    /**
     * @param $connection
     * @return QueueInterface
     * @throws \Exception
     */
    protected function resolveConnection($connection): QueueInterface
    {
        [$driver, $name] = explode(':', $connection);

        if (!isset($this->config[$driver])) {
            throw new \Exception("Queue driver $driver have not been configured");
        }

        if (!isset($this->config[$driver][$name])) {
            throw new \Exception("Undefined connection name $name");
        }

        if (!isset($this->connectors[$driver])) {
            throw new \Exception("Undefined connector for queue driver $driver");
        }

        return $this->connectors[$driver]->connect($this->config[$driver][$name], $this->serializer);
    }

    /**
     * @param ConnectorInterface $connector
     */
    public function addConnector(ConnectorInterface $connector)
    {
        $this->connectors[$connector->getName()] = $connector;
    }

    /**
     * @param JobInterface $job
     * @throws \Exception
     */
    public function dispatch(JobInterface $job)
    {
        $queue = $this->getConnection($job->getConnection());
        $queue->push($job, null, $job->getQueue());
    }

    public function close($connection)
    {
        [$driver, $name] = explode(':', $connection);

        $this->connectors[$driver]->close($this->config[$driver][$name]);
    }
}