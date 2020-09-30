<?php


namespace Label305\Tasks\Persistence\Tasks;


use Carbon\Carbon;
use Label305\Tasks\Enums\State;
use Label305\Tasks\Task;

class EloquentTaskRepository implements TaskRepository
{
    const DEFAULT_PAGE_SIZE = 25;

    /**
     * @param Task $task
     * @return Task
     */
    public function store(Task $task): Task
    {
        $eloquentTask = EloquentTask::fromTask($task);

        $eloquentTask->save();

        return $eloquentTask->toTask();
    }

    /**
     * @param Task $task
     * @return Task
     */
    public function update(Task $task): Task
    {
        $eloquentTask = EloquentTask::fromTask($task);

        $eloquentTask->save();

        return $eloquentTask->toTask();
    }

    /**
     * @param int $page
     * @return Task[]
     */
    public function getPage(int $page): array
    {
        $limit = self::DEFAULT_PAGE_SIZE;

        /** @var EloquentTask[] $eloquentTasks */
        $builder = EloquentTask::orderBy('updated_at', 'DESC');

        /** @var EloquentTask[] $eloquentTasks */
        $eloquentTasks = $builder
            ->skip($page * $limit)
            ->limit($limit)
            ->get();

        $result = [];
        foreach ($eloquentTasks as $eloquentTask) {
            $result[] = $eloquentTask->toTask();
        }

        return $result;
    }

    public function totalNumPages():int
    {
        return ceil(EloquentTask::count() / self::DEFAULT_PAGE_SIZE);
    }

    public function getAllBetween(int $page, Carbon $from, Carbon $till)
    {
        $limit = self::DEFAULT_PAGE_SIZE;

        /** @var EloquentTask[] $eloquentTasks */
        $builder = EloquentTask::where('created_at', '>=', $from)->where('created_at', '<', $till);

        /** @var EloquentTask[] $eloquentTasks */
        $eloquentTasks = $builder
            ->skip($page * $limit)
            ->limit($limit)
            ->get();

        $result = [];
        foreach ($eloquentTasks as $eloquentTask) {
            $result[] = $eloquentTask->toTask();
        }

        return $result;
    }

    public function totalNumPagesBetween(Carbon $from, Carbon $till)
    {
        $count = EloquentTask::where('created_at', '>=', $from)->where('created_at', '<', $till)->count();
        return ceil($count / self::DEFAULT_PAGE_SIZE);
    }


    /**
     * @param int $id
     * @return Task|null
     */
    public function findById(int $id):?Task
    {
        /** @var EloquentTask $eloquentTask */
        $eloquentTask = EloquentTask::where('id', $id)
            ->first();

        if ($eloquentTask !== null) {
            return $eloquentTask->toTask();
        }

        return null;
    }

    /**
     * @param $type
     * @return int
     */
    public function getUnfinishedTasksCountByType($type): int
    {
        return EloquentTask::where('state', 'NOT LIKE', State::FINISHED)
            ->where('type', $type)->count();
    }


    public function findLongRunningTasks(): array
    {
        $dateTimeString = Carbon::now()->subMinutes(30)->toDateTimeString();
        $eloquentTasks = EloquentTask::where('state', 'NOT LIKE', State::FINISHED)
            ->where('state', 'like', State::STARTED)
            ->where('is_long_running', false)
            ->whereRaw('updated_at < ' . '\'' . $dateTimeString . '\'')
            ->get();


        $result = [];
        foreach ($eloquentTasks as $eloquentTask) {
            $result[] = $eloquentTask->toTask();
        }

        return $result;
    }

    public function removeAllBefore(Carbon $carbon): int
    {
        return EloquentTask::whereDate('created_at', '<', $carbon)->delete();
    }


}
