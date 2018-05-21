<?php

namespace Bezb\QueueBundle\Worker\ConnectionKeeper;

/**
 * Interface ConnectKeeperInterface
 * @package Bezb\QueueBundle\Worker\ConnectKeeper
 */
interface ConnectionKeeperInterface
{
    /**
     * @return mixed
     */
    public function closeConnections();
}