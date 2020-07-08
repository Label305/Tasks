<?php


namespace Label305\Tasks\Persistence\ContinuousTasks;


use Label305\Tasks\Persistence\EagerFetch;
use Label305\Tasks\ContinuousTask;

interface ContinuousTaskRepository
{

    /**
     * @param ContinuousTask $continuousTask
     * @return ContinuousTask
     */
    public function store(ContinuousTask $continuousTask): ContinuousTask;

    /**
     * @param ContinuousTask $continuousTask
     * @return ContinuousTask
     */
    public function update(ContinuousTask $continuousTask): ContinuousTask;

    /**
     * @param int $page
     * @return ContinuousTask[]
     */
    public function getPage(int $page): array;

    /**
     * @param int $id
     * @return ContinuousTask|null
     */
    public function findById(int $id):?ContinuousTask;

    /**
     * @return int
     */
    public function totalNumPages(): int;

    /**
     * @param string $
     * @return ContinuousTask[]
     */
    public function findByState(string $state): array;

}
