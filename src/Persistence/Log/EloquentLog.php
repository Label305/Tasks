<?php


namespace Label305\Tasks\Persistence\Log;


use Label305\Tasks\Persistence\Tasks\EloquentTask;

class EloquentLog extends Model
{
    /**
     * @return string
     */
    public function getTable()
    {
        return 'logs';
    }

    public function tasks()
    {
        return $this->belongsTo(EloquentTask::class);
    }

    public static function fromLog(Log $log): EloquentLog
    {
        $eloquentLog = new EloquentLog();
        $eloquentLog->id = $log->getId();
        $eloquentLog->exists = $log->getId() !== null;
        $eloquentLog->blob = $log->getBlob();
        $eloquentLog->task_id = $log->getTaskId();

        return $eloquentLog;
    }

    public function toLog(): Log
    {
        $log = new Log();
        $log->setId($this->id);
        $log->setTaskId($this->task_id);
        $log->setBlob($this->blob);

        return $log;
    }
}