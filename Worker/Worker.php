<?php

namespace Bezb\QueueBundle\Worker;

use Bezb\QueueBundle\JobInterface;
use Bezb\QueueBundle\QueueHandler;
use Bezb\QueueBundle\QueueManager;
use Bezb\QueueBundle\Worker\ConnectionKeeper\ConnectionKeeperInterface;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Class Worker
 * @package Bezb\QueueBundle\Worker
 */
class Worker
{
    /**
     * @var QueueManager
     */
    protected $manager;

    /**
     * @var QueueHandler
     */
    protected $handler;

    /**
     * @var ConnectionKeeperInterface[]
     */
    protected $connectionKeepers = [];

    /**
     * @var array
     */
    protected $processes = [];

    /**
     * Worker constructor.
     * @param QueueManager $manager
     * @param QueueHandler $handler
     * @param iterable $connectionKeepers
     */
    public function __construct(QueueManager $manager, QueueHandler $handler, iterable $connectionKeepers)
    {
        $this->manager = $manager;
        $this->handler = $handler;
        $this->connectionKeepers = $connectionKeepers;
    }

    public function runDaemon($connectionName, $queueName, WorkerOptions $options)
    {
        pcntl_signal(SIGCHLD, [$this, 'signalHandler']);

        while (true) {

            // Do not take next tasks while do not get free workers limit
            while (count($this->processes) >= $options->getProcessLimit()) {
                usleep(10000);
            }

            $job = $this->manager->getConnection($connectionName)->pop($queueName);

            if ($job) {
                // Close active external connections before forking, to prevent their closing when child exits
                $this->closeConnections($connectionName);

                $this->executeJob($job, $options);
            } else {

                // Sleep if have not got a new job
                sleep($options->getSleep());
            }

        }
    }

    public function executeJob(JobInterface $job, WorkerOptions $options)
    {
        $pid = pcntl_fork();

        if ($pid == -1) {
            throw new Exception('Could not fork child process', 500);
        } elseif ($pid) {
            $this->processes[$pid] = true;
        } else {
            try {
                $this->handler->handle($job);
            } catch (\Exception $e) {
                var_dump($e->getMessage());
            }

            exit(0);
        }
    }

    /**
     * @param $connectionName
     */
    protected function closeConnections($connectionName)
    {
        foreach ($this->connectionKeepers as $connectionKeeper) {
            $connectionKeeper->closeConnections();
        }

        // Also close active queue connection by the same reason
        $this->manager->close($connectionName);
    }

    protected function signalHandler($signal)
    {
        if (SIGCHLD === $signal) {
            $this->waitChildrenProcesses();
        }
    }

    protected function waitChildrenProcesses()
    {
        do {
            $pid = pcntl_waitpid(-1, $status, WNOHANG);

            if ($pid > 0 && isset($this->processes[$pid])) {
                unset($this->processes[$pid]);
            }
        } while ($pid > 0);
    }
}