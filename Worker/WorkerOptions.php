<?php

namespace Bezb\QueueBundle\Worker;

class WorkerOptions
{
    /**
     * @var int
     */
    protected $sleep = 0;

    /**
     * @var int
     */
    protected $processLimit = 10;

    /**
     * @return mixed
     */
    public function getSleep(): int
    {
        return $this->sleep;
    }

    /**
     * @param $sleep
     * @return WorkerOptions
     */
    public function setSleep(int $sleep): WorkerOptions
    {
        $this->sleep = $sleep;

        return $this;
    }

    /**
     * @return int
     */
    public function getProcessLimit(): int
    {
        return $this->processLimit;
    }

    /**
     * @param int $processLimit
     * @return WorkerOptions
     */
    public function setProcessLimit(int $processLimit): WorkerOptions
    {
        $this->processLimit = $processLimit;

        return $this;
    }


}