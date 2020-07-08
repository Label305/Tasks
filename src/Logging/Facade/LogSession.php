<?php


namespace Label305\Tasks\Logging\Facade;


use Illuminate\Support\Facades\Facade;

class LogSession extends Facade
{

    public static function getFacadeAccessor()
    {
        return 'LogSession';
    }
}
