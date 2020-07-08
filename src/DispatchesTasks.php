<?php


namespace Label305\Tasks;


use Label305\Tasks\Persistence\Tasks\TaskRepository;
use Label305\Tasks\Support\Queues;
use Label305\Tasks\Enums\Result;
use Label305\Tasks\Enums\State;

trait DispatchesTasks
{

    public function dispatchTask($job, $queue = Queues::DEFAULT): Task
    {
        $task = $this->create($job);
        $task = $this->persist($task);
        $task->onQueue($queue);
        $this->dispatch($task);

        return $task;
    }

    private function create($job): Task
    {
        $task = new Task($job);
        $task->setResult(Result::CREATED);
        $task->setState(State::CREATED);
        $task->setType($this->getTypeFromJob($job));

        return $task;
    }

    private function persist(Task $task): Task
    {
        /** @var TaskRepository $taskRepository */
        $taskRepository = app(TaskRepository::class);

        return $taskRepository->store($task);
    }

    private function getTypeFromJob($job): string
    {
        /* Using a reflection class is faster: https://coderwall.com/p/cpxxxw/php-get-class-name-without-namespace */
        return (new \ReflectionClass($job))->getShortName();
    }

}
