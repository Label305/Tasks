<?php

namespace Label305\Tasks\Support;


use Carbon\Carbon;
use Label305\Tasks\Enums\State;
use Label305\Tasks\Persistence\Tasks\TaskRepository;
use Label305\Tasks\Task;

class TaskState
{
    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var Task
     */
    private $task;

    /**
     * TaskState constructor.
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function register(Task $task)
    {
        $this->task = $task;
    }

    public function started()
    {
        $this->assert();

        if (State::hasHigherPriority($this->task->getState(), State::STARTED)) {
            $this->task->setState(State::STARTED);
        }
        $this->task->setLastStartedAt(Carbon::now());

        $this->taskRepository->update($this->task);
    }

    /**
     * Please be very careful using this, it will call the db every time.
     */
    public function alive()
    {
        $this->assert();
        $this->task->setState($this->task->getState());
        $this->taskRepository->update($this->task);
    }

    public function finished()
    {
        $this->touchState(State::FINISHED);
    }

    private function assert()
    {
        if ($this->task === null) {
            throw new AssertionException('Task not available in TaskState manager');
        }
    }

    /**
     * @param $state
     */
    private function touchState($state): void
    {
        $this->assert();

        if (State::hasHigherPriority($this->task->getState(), $state)) {
            $this->task->setState($state);
            $this->taskRepository->update($this->task);
        }
    }
}
