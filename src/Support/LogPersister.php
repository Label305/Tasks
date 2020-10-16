<?php


namespace Label305\Tasks\Support;


use Label305\Tasks\Task;

interface LogPersister
{
    /**
     * @param Task $task
     * @return bool
     */
    public function persist(Task $task): bool;
}