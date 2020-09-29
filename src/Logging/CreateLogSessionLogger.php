<?php


namespace Label305\Tasks\Logging;


use Label305\Tasks\Logging\Facade\LogSession as LogSessionFacade;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class CreateLogSessionLogger
{
    /**
     * @param array $config
     * @return Logger
     */
    public function __invoke(array $config)
    {
        return new Logger('tasklog', [new StreamHandler(LogSessionFacade::getTaskLocation())]);
    }
}