<?php


namespace Label305\Tasks\Support;


use Label305\Tasks\Persistence\Log\Log;
use Label305\Tasks\Persistence\Log\LogRepository;
use Label305\Tasks\Task;

class EloquentLogPersister implements LogPersister
{
    /**
     * @var LogRepository
     */
    private $logRepository;

    /**
     * EloquentLogPersister constructor.
     * @param LogRepository $logRepository
     */
    public function __construct(LogRepository $logRepository)
    {
        $this->logRepository = $logRepository;
    }

    public function persist(Task $task): bool
    {
        $log = new Log();
        $log->setTaskId($task->getId());
        $blob = file_get_contents($task->getLocalPathForLog());
        if ($blob === false) {
            return false;
        }
        $log->setBlob($blob);
        return $this->logRepository->store($log) ? true : false;
    }
}