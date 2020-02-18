<?php

namespace Bezb\QueueBundle\Worker\ConnectionKeeper;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Class DoctrineKeeper
 * @package Bezb\QueueBundle\Worker\ConnectKeeper
 */
class DoctrineKeeper implements ConnectionKeeperInterface
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * DoctrineKeeper constructor.
     * @param ManagerRegistry $doctrine
     */
    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return mixed|void
     */
    public function closeConnections()
    {
        /** @var EntityManagerInterface $manager */
        foreach ($this->doctrine->getEntityManagers() as $name => $manager) {
            if (false === $manager->isOpen()) {
                continue;
            }

            $manager->getConnection()->close();
        }
    }
}