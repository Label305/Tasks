<?php


namespace Label305\Tasks\Support;


use Label305\Tasks\Exceptions\AssertionException;
use Label305\Tasks\Persistence\Tasks\TaskRepository;
use Label305\Tasks\Enums\Result;
use Label305\Tasks\Task;

class TaskResult
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
     * TaskResult constructor.
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
        $this->touchResult(Result::STARTED);
    }

    public function finished()
    {
        $this->touchResult(Result::FINISHED);
    }

    public function warning()
    {
        $this->touchResult(Result::WARNING);
    }

    public function errored()
    {
        $this->touchResult(Result::ERROR);
    }

    /**
     * Add random meta data to the task result
     * @param $key
     * @param $value
     */
    public function addMetaData($key, $value) {
        $meta = $this->task->getMetaData();
        $meta[$key] = $value;
        $this->task->setMetaData($meta);
        $this->taskRepository->update($this->task);
    }

    private function assert()
    {
        if ($this->task === null) {
            throw new AssertionException('Task not available in TaskResult manager');
        }
    }

    /**
     * @param $result
     */
    private function touchResult($result): void
    {
        $this->assert();

        if (Result::hasHigherPriority($this->task->getResult(), $result)) {
            $this->task->setResult($result);
            $this->taskRepository->update($this->task);
        }
    }
}
