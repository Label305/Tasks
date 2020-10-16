<?php


namespace Label305\Tasks;


use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Label305\Tasks\Exceptions\AssertionException;
use Label305\Tasks\Logging\Facade\LogSession;
use Label305\Tasks\Support\Facades\TaskResult;
use Label305\Tasks\Support\Facades\TaskState;

class ContinuousTask extends Task
{

    public function handle()
    {
        if ($this->getId() === null) {
            throw new AssertionException('Task triggered before ID is available');
        }

        LogSession::register($this);
        TaskResult::register($this);
        TaskState::register($this);

        TaskResult::started();
        TaskState::started();

        if (empty($this->queue)) {
            Log::info('(re-)Started task at ' . date('Y-m-d H:i:s') . ' on queue "default"');
        } else {
            Log::info('(re-)Started task at ' . date('Y-m-d H:i:s') . ' on queue "' . $this->queue . '"');
        }

        try {
            $this->dispatch($this->getWrappedJob());
        } catch (\Exception $e) {
            TaskResult::errored();
            Log::error($e);
            throw $e;
        } finally {
            if ($this->shouldFinishTask()) {
                TaskResult::finished();
                TaskState::finished();
            }

            Log::info('Ended task at ' . date('Y-m-d H:i:s') . ' with peak memory usage ' . (memory_get_peak_usage(1) / 1000000) . 'MB');
            TaskResult::addMetaData('peak_memory_usage_MB', round((memory_get_peak_usage(1) / 1000000)));


            LogSession::persist();

            if ($this->shouldFinishTask()) {
                LogSession::flush();
            }
        }
    }

    /**
     * @return bool
     */
    public function shouldFinishTask(): bool
    {
        return $this->getCreatedAt()->lessThan((Carbon::yesterday()->endOfDay()));
    }

}
