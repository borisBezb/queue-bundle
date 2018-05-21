<?php

namespace Bezb\QueueBundle\Command;

use Bezb\QueueBundle\Worker\{ Worker, WorkerOptions };
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class QueueWorkerCommand
 * @package Bezb\QueueBundle\Command
 */
class QueueWorkerCommand extends Command
{
    /**
     * @var Worker
     */
    protected $worker;

    public function __construct(Worker $worker)
    {
        $this->worker = $worker;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('bezb:queue:worker')
            ->setDescription('Run daemon process to handle queue jobs')
            ->addArgument('connection', InputArgument::REQUIRED)
            ->addArgument('queue', InputArgument::OPTIONAL)
            ->addArgument('sleep', InputArgument::OPTIONAL, 'Sleep time between jobs', 0)
            ->addArgument('process_limit', InputArgument::OPTIONAL, 'Limit of parallel running processes', 8)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $connectionName = $input->getArgument('connection');
        $queueName      = $input->getArgument('queue');

        $options = (new WorkerOptions())
            ->setSleep($input->getArgument('sleep'))
            ->setProcessLimit($input->getArgument('process_limit'))
        ;

        $this->worker->runDaemon($connectionName, $queueName, $options);
    }
}