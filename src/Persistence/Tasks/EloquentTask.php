<?php


namespace Label305\Tasks\Persistence\Tasks;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Label305\Tasks\Task;

/**
 * @property int id
 * @property string job
 * @property string state
 * @property string result
 * @property string|null meta_data
 * @property bool is_long_running
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string type
 * @property Carbon|null last_started_at
 */
class EloquentTask extends Model
{

    public $dates = [
        'created_at',
        'updated_at',
        'last_started_at'
    ];

    public function getTable()
    {
        return 'tasks';
    }

    public static function fromTask(Task $task): EloquentTask
    {
        $eloquentTask = new EloquentTask();
        $eloquentTask->id = $task->getId();
        $eloquentTask->exists = $task->getId() !== null;
        $eloquentTask->type = $task->getType();
        $eloquentTask->state = $task->getState();
        $eloquentTask->result = $task->getResult();
        $eloquentTask->meta_data = is_array($task->getMetaData()) ? json_encode($task->getMetaData()) : null;
        $eloquentTask->last_started_at = $task->getLastStartedAt();
        $eloquentTask->is_long_running = $task->isLongRunning();
        $eloquentTask->job = serialize($task->getWrappedJob());

        return $eloquentTask;
    }

    public function toTask(): Task
    {
        $task = new Task(
            unserialize($this->job)
        );
        $task->setId($this->id);
        $task->setType($this->type);
        $task->setState($this->state);
        $task->setResult($this->result);
        $task->setMetaData($this->meta_data === null ? null : json_decode($this->meta_data, true));
        $task->setIsLongRunning($this->is_long_running);
        $task->setCreatedAt($this->created_at);
        $task->setUpdatedAt($this->updated_at);
        $task->setLastStartedAt($this->last_started_at);

        return $task;
    }

}
