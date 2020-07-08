<?php


namespace Label305\Tasks\Support\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static addMetaData(string $key, int $value)
 */
class TaskResult extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'TaskResult';
    }


}
