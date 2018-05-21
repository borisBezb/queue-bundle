<?php

namespace Bezb\QueueBundle\Connector;

use Bezb\QueueBundle\Queue\QueueInterface;
use Bezb\QueueBundle\Serializer\SerializerInterface;

/**
 * Interface ConnectorInterface
 * @package Bezb\QueueBundle\Connector
 */
interface ConnectorInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param array $config
     * @param SerializerInterface $serializer
     * @return QueueInterface
     */
    public function connect(array $config, SerializerInterface $serializer): QueueInterface;

    /**
     * @param array $config
     * @return mixed
     */
    public function close(array $config);
}