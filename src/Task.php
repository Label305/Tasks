<?php


namespace Label305\Tasks;


use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Queue\InteractsWithQueue;
use Label305\Tasks\Exceptions\AssertionException;
use Label305\Tasks\Logging\Facade\LogSession;
use Illuminate\Support\Facades\Log;
use Label305\Tasks\Persistence\Log\Log as StoredLog;
use Label305\Tasks\Support\Facades\TaskResult;
use Label305\Tasks\Support\Facades\TaskState;

class Task implements ShouldQueue
{
    use Queueable, DispatchesJobs, InteractsWithQueue;

    /** @var int|null */
    private $id;

    /** @var string|null */
    private $state;

    /** @var string|null */
    private $result;

    /** @var array|null */
    private $metaData;

    /** @var string */
    private $type;

    /** @var mixed */
    private $wrappedJob;

    /** @var Carbon|null */
    private $updatedAt;

    /** @var Carbon|null */
    private $createdAt;

    /** @var Carbon|null */
    private $lastStartedAt;

    /** @var bool */
    private $isLongRunning = false;

    /**
     * @var StoredLog|null log
     */
    private $log;

    /**
     * Task constructor.
     * @param $wrappedJob
     */
    public function __construct($wrappedJob)
    {
        $this->wrappedJob = $wrappedJob;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return string|null
     */
    public function getResult(): ?string
    {
        return $this->result;
    }

    /**
     * @param string $result
     */
    public function setResult(string $result)
    {
        $this->result = $result;
    }

    /**
     * @return array|null
     */
    public function getMetaData(): ?array
    {
        return $this->metaData === null ? [] : $this->metaData;
    }

    /**
     * @param array|null $metaData
     */
    public function setMetaData(?array $metaData): void
    {
        $this->metaData = $metaData;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getWrappedJob()
    {
        return $this->wrappedJob;
    }

    /**
     * @param mixed $wrappedJob
     */
    public function setWrappedJob($wrappedJob)
    {
        $this->wrappedJob = $wrappedJob;
    }

    /**
     * @return Carbon|null
     */
    public function getUpdatedAt():?Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @param Carbon|null $updatedAt
     */
    public function setUpdatedAt(?Carbon $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt():?Carbon
    {
        return $this->createdAt;
    }

    /**
     * @param Carbon|null $createdAt
     */
    public function setCreatedAt(?Carbon $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return Carbon|null
     */
    public function getLastStartedAt():?Carbon
    {
        return $this->lastStartedAt;
    }

    /**
     * @param Carbon|null $lastStartedAt
     */
    public function setLastStartedAt(?Carbon $lastStartedAt)
    {
        $this->lastStartedAt = $lastStartedAt;
    }

    /**
     * @return bool
     */
    public function isLongRunning(): bool
    {
        return $this->isLongRunning;
    }

    /**
     * @param bool|null $isLongRunning
     */
    public function setIsLongRunning(bool $isLongRunning)
    {
        $this->isLongRunning = $isLongRunning;
    }


    public function handle()
    {
        if ($this->id === null) {
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
            $this->dispatch($this->wrappedJob);
        } catch (\Exception $e) {
            TaskResult::errored();
            Log::error($e);
            throw $e;
        } finally {
            TaskResult::finished();
            TaskState::finished();

            Log::info('Ended task at ' . date('Y-m-d H:i:s') . ' with peak memory usage ' . (memory_get_peak_usage(1) / 1000000) . 'MB');
            TaskResult::addMetaData('peak_memory_usage_MB', round((memory_get_peak_usage(1) / 1000000)));

            LogSession::persist();
            LogSession::flush();
        }
    }

    /**
     * @return string
     * @throws AssertionException
     */
    public function getLocalPathForLog(): string
    {
        if ($this->id === null) {
            throw new AssertionException('Requesting path for local log before persisting');
        }

        return storage_path() . '/logs/task-' . $this->id . '.log';
    }

    /**
     * @return string
     */
    public function getPersistentPathForLog(): string
    {
        if ($this->id === null) {
            throw new AssertionException('Requesting path for persistent log before persisting');
        }

        return sprintf(
            '/tasks/%s/%s/task_%s.log',
            $this->createdAt->format('Y'),
            $this->createdAt->format('m'),
            $this->id
        );
    }

    /**
     * @param StoredLog $log
     */
    public function setLog(StoredLog $log)
    {
        $this->log = $log;
    }

    /**
     * @return StoredLog|null
     */
    public function getLog(): ?StoredLog
    {
        return $this->log;
    }

}
