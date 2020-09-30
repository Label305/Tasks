<?php


namespace Label305\Tasks;


use Label305\Tasks\Persistence\ContinuousTasks\ContinuousTaskRepository;
use Label305\Tasks\Support\Queues;
use Label305\Tasks\Enums\Result;
use Label305\Tasks\Enums\State;

trait DispatchesContinuousTasks
{

    public function dispatchContinuousTask($job): ContinuousTask
    {
        $continuousTask = $this->create($job);
        $continuousTask = $this->persist($continuousTask);
        $this->dispatch($continuousTask->onQueue(Queues::CONTINUOUS));

        return $continuousTask;
    }

    private function create($job): ContinuousTask
    {
        $continuousTask = new ContinuousTask($job);
        $continuousTask->setResult(Result::CREATED);
        $continuousTask->setState(State::CREATED);
        $continuousTask->setType($this->getTypeFromJob($job));

        return $continuousTask;
    }

    private function persist(ContinuousTask $ContinuousTask): ContinuousTask
    {
        /** @var ContinuousTaskRepository $continuousTaskRepository */
        $continuousTaskRepository = app(ContinuousTaskRepository::class);

        return $continuousTaskRepository->store($ContinuousTask);
    }

    private function getTypeFromJob($job): string
    {
        /* Using a reflection class is faster: https://coderwall.com/p/cpxxxw/php-get-class-name-without-namespace */
        return (new \ReflectionClass($job))->getShortName();
    }

}
