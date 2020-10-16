<?php


namespace Label305\Tasks\Logging;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use Label305\Tasks\Support\LogPersister;
use Label305\Tasks\Task;

class LogSession
{

    /**
     * @var Task|null
     */
    private $task = null;

    /**
     * @var LogPersister
     */
    private $persister;

    /**
     * LogSession constructor.
     * @param LogPersister $persister
     */
    public function __construct(LogPersister $persister)
    {
        $this->persister = $persister;
    }

    /**
     * Start a new session
     */
    public function register(Task $task)
    {
        $this->task = $task;

        touch($this->task->getLocalPathForLog());

    }

    /**
     * Persist the new session
     */
    public function persist()
    {
//        $fileHandle = fopen($this->task->getLocalPathForLog(), 'r');

        $this->persister->persist($this->task);

//        /**
//         * @var $filesystem FilesystemAdapter
//         */
//        $filesystem->put($path, $fileHandle, 'public');
    }

    /**
     * Remove the file from local filesystem
     */
    public function flush()
    {
        unlink($this->task->getLocalPathForLog());
    }

    public function getTaskLocation()
    {
        return $this->task !== null ? $this->task->getLocalPathForLog() : storage_path('logs/laravel.log');
    }

}
