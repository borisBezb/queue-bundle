<?php

namespace Bezb\QueueBundle;

interface JobInterface
{
    /**
     * @return string
     */
    public function getConnection(): ?string;

    /**
     * @param string $connection
     * @return JobInterface
     */
    public function setConnection(string $connection): JobInterface;

    /**
     * @return null|string
     */
    public function getQueue(): ?string;

    /**
     * @param string $queue
     * @return $this
     */
    public function setQueue(string $queue): JobInterface;

    /**
     * @return string
     */
    public function getHandler(): string;

    /**
     * @param string $handler
     * @return $this
     */
    public function setHandler(string $handler): JobInterface;
}