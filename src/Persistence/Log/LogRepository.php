<?php


namespace Label305\Tasks\Persistence\Log;


interface LogRepository
{
    /**
     * @param Log $log
     * @return Log
     */
    public function store(Log $log): Log;
}