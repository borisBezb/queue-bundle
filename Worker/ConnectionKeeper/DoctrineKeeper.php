<?php

namespace Bezb\QueueBundle\Worker\ConnectionKeeper;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class DoctrineKeeper
 * @package Bezb\QueueBundle\Worker\ConnectKeeper
 */
class DoctrineKeeper implements ConnectionKeeperInterface
{
    /**
     * @var RegistryInterface
     */
    protected $doctrine;

    /**
     * DoctrineKeeper constructor.
     * @param RegistryInterface $doctrine
     */
    public function __construct(RegistryInterface $doctrine)
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