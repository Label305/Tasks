<?php


namespace Label305\Tasks\Persistence\Tasks;


use Carbon\Carbon;
use Label305\Tasks\Task;

interface TaskRepository
{

    /**
     * @param Task $task
     * @return Task
     */
    public function store(Task $task): Task;

    /**
     * @param Task $task
     * @return Task
     */
    public function update(Task $task): Task;

    /**
     * @param int $page
     * @return array|Task[]
     */
    public function getPage(int $page):array;

    /**
     * @param int $page
     * @param Carbon $from
     * @param Carbon $till
     * @return array|Task[]
     */
    public function getAllBetween(int $page, Carbon $from, Carbon $till);

    /**
     * @param Carbon $from
     * @param Carbon $till
     * @return array|Task[]
     */
    public function totalNumPagesBetween(Carbon $from, Carbon $till);

    /**
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id):?Task;

    /**
     * @param $type
     * @return int
     */
    public function getUnfinishedTasksCountByType($type):int;

    /**
     * @return array
     */
    public function findLongRunningTasks(): array;

    /**
     * @param Carbon $carbon
     * @return int count
     */
    public function removeAllBefore(Carbon $carbon): int;

}
