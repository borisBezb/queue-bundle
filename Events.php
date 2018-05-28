<?php

namespace Bezb\QueueBundle;

class Events
{
    const PROCESS_LIMIT = 'queue.event.worker.process_limit';
    const BEFORE_DO_JOB = 'queue.event.worker.before_do_job';
}