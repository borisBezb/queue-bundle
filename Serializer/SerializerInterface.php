<?php

namespace Bezb\QueueBundle\Serializer;

use Bezb\QueueBundle\JobInterface;

/**
 * Interface SerializerInterface
 * @package Bezb\QueueBundle\Serializer
 */
interface SerializerInterface
{
    /**
     * @param JobInterface $job
     * @return string
     */
    public function serialize(JobInterface $job): string;

    /**
     * @param string $string
     * @return JobInterface
     */
    public function unserialize(string $string): JobInterface;
}