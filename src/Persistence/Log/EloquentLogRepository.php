<?php


namespace Label305\Tasks\Persistence\Log;


class EloquentLogRepository implements LogRepository
{

    /**
     * @param Log $log
     * @return Log
     */
    public function store(Log $log): Log
    {
        $eloquentLog = EloquentLog::fromLog($log);
        $eloquentLog->save();

        return $eloquentLog->toLog();
    }
}