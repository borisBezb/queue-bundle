<?php

namespace Bezb\QueueBundle;

/**
 * Class Job
 * @package Bezb\QueueBundle
 */
class Job implements JobInterface
{
    /**
     * @var string
     */
    protected $connection;

    /**
     * @var string
     */
    protected $queue;

    /**
     * @var string
     */
    protected $handler;

    /**
     * @return null|string
     */
    public function getConnection(): ?string
    {
        return $this->connection;
    }

    /**
     * @param string $connection
     * @return $this
     */
    public function setConnection(string $connection): JobInterface
    {
        $this->connection = $connection;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getQueue(): ?string
    {
        return $this->queue;
    }

    /**
     * @param string $queue
     * @return $this
     */
    public function setQueue(string $queue): JobInterface
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * @return string
     */
    public function getHandler(): string
    {
        return $this->handler;
    }

    /**
     * @param string $handler
     * @return $this
     */
    public function setHandler(string $handler): JobInterface
    {
        $this->handler = $handler;

        return $this;
    }

    public function __sleep()
    {
        $properties = (new \ReflectionClass($this))->getProperties();

        foreach ($properties as $property) {
            $property->setAccessible(true);

            $value = $property->getValue($this);

            var_dump($value);
        }


        exit;
    }

    public function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }
}