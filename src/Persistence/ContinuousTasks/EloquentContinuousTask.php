<?php


namespace Label305\Tasks\Persistence\ContinuousTasks;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Label305\Tasks\ContinuousTask;

/**
 * @property int id
 * @property string job
 * @property string state
 * @property string result
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property string type
 * @property bool is_continuous
 * @property Carbon|null last_started_at
 */
class EloquentContinuousTask extends Model
{
    public $dates = [
        'created_at',
        'updated_at',
        'last_started_at'
    ];

    public function getTable(): string
    {
        return 'tasks';
    }

    public static function fromContinuousTask(ContinuousTask $continuousTask): EloquentContinuousTask
    {
        $eloquentContinuousTask = new EloquentContinuousTask();
        $eloquentContinuousTask->id = $continuousTask->getId();
        $eloquentContinuousTask->exists = $continuousTask->getId() !== null;
        $eloquentContinuousTask->is_continuous = true;
        $eloquentContinuousTask->type = $continuousTask->getType();
        $eloquentContinuousTask->state = $continuousTask->getState();
        $eloquentContinuousTask->result = $continuousTask->getResult();
        $eloquentContinuousTask->last_started_at = $continuousTask->getLastStartedAt();
        $eloquentContinuousTask->is_long_running = $continuousTask->isLongRunning();
        $eloquentContinuousTask->job = serialize($continuousTask->getWrappedJob());

        return $eloquentContinuousTask;
    }

    public function toContinuousTask(): ContinuousTask
    {
        $continuousTask = new ContinuousTask(
            unserialize($this->job)
        );
        $continuousTask->setId($this->id);
        $continuousTask->setType($this->type);
        $continuousTask->setState($this->state);
        $continuousTask->setResult($this->result);
        $continuousTask->setCreatedAt($this->created_at);
        $continuousTask->setUpdatedAt($this->updated_at);
        $continuousTask->setLastStartedAt($this->last_started_at);
        $continuousTask->setIsLongRunning(false);

        if ($this->relationLoaded('school') && $this->school) {
            $continuousTask->setSchool($this->school->toSchool());
        }

        return $continuousTask;
    }

}
