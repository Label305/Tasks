<?php


namespace Label305\Tasks\Logging;


use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Log;
use Label305\Tasks\Task;

class LogSession
{

    /**
     * @var Task|null
     */
    private $task = null;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * LogSession constructor.
     * @param FilesystemAdapter $filesystem
     */
    public function __construct(FilesystemAdapter $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Start a new session
     */
    public function register(Task $task)
    {
        $this->task = $task;

        touch($this->task->getLocalPathForLog());

//        Log::useFiles($this->task->getLocalPathForLog());
    }

    /**
     * Persist the new session
     */
    public function persist(string $path)
    {
        $fileHandle = fopen($this->task->getLocalPathForLog(), 'r');

        $this->filesystem->put($path, $fileHandle, 'public');
    }

    /**
     * Remove the file from local filesystem
     */
    public function flush()
    {
        unlink($this->task->getLocalPathForLog());
    }

}
