<?php


namespace Label305\Tasks\Persistence\ContinuousTasks;


use Label305\Tasks\ContinuousTask;

class EloquentContinuousTaskRepository implements ContinuousTaskRepository
{
    const DEFAULT_PAGE_SIZE = 25;

    /**
     * @param ContinuousTask $continuousTask
     * @return ContinuousTask
     */
    public function store(ContinuousTask $continuousTask): ContinuousTask
    {
        $eloquentContinuousTask = EloquentContinuousTask::fromContinuousTask($continuousTask);

        $eloquentContinuousTask->save();

        return $eloquentContinuousTask->toContinuousTask();
    }

    /**
     * @param ContinuousTask $continuousTask
     * @return ContinuousTask
     */
    public function update(ContinuousTask $continuousTask): ContinuousTask
    {
        $eloquentContinuousTask = EloquentContinuousTask::fromContinuousTask($continuousTask);

        $eloquentContinuousTask->save();

        return $eloquentContinuousTask->toContinuousTask();
    }

    /**
     * @param int $page
     * @return ContinuousTask[]
     */
    public function getPage(int $page): array
    {
        $limit = self::DEFAULT_PAGE_SIZE;

        /** @var EloquentContinuousTask[] $eloquentContinuousTasks */
        $eloquentContinuousTasks = EloquentContinuousTask::orderBy('updated_at', 'DESC')
            ->where('is_continuous', true)
            ->skip($page * $limit)
            ->limit($limit)
            ->get();

        $result = [];
        foreach ($eloquentContinuousTasks as $eloquentContinuousTask) {
            $result[] = $eloquentContinuousTask->toContinuousTask();
        }

        return $result;
    }

    /**
     * @return int
     */
    public function totalNumPages(): int
    {
        return ceil(EloquentContinuousTask::where('is_continuous', true)->count() / self::DEFAULT_PAGE_SIZE);
    }

    /**
     * @param int $id
     * @return ContinuousTask|null
     */
    public function findById(int $id):?ContinuousTask
    {
        /** @var EloquentContinuousTask $eloquentContinuousTask */
        $eloquentContinuousTask = EloquentContinuousTask::where('id', $id)->where('is_continuous', true)
            ->first();

        if ($eloquentContinuousTask !== null) {
            return $eloquentContinuousTask->toContinuousTask();
        }

        return null;
    }

    /**
     * @param string $state
     * @return ContinuousTask[]
     */
    public function findByState(string $state): array
    {

        /** @var EloquentContinuousTask[] $eloquentContinuousTasks */
        $eloquentContinuousTasks = EloquentContinuousTask::where('state', $state)
            ->where('is_continuous', true)
            ->get();

        $result = [];
        foreach ($eloquentContinuousTasks as $eloquentContinuousTask) {
            $result[] = $eloquentContinuousTask->toContinuousTask();
        }

        return $result;
    }
}
