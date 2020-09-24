<?php

namespace Label305\Tasks;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Label305\Tasks\Logging\LogSession;
use Label305\Tasks\Persistence\Tasks\EloquentTaskRepository;
use Label305\Tasks\Persistence\Tasks\TaskRepository;
use Label305\Tasks\Support\TaskResult;
use Label305\Tasks\Support\TaskState;

class TasksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/routes.php';

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->app->singleton('LogSession', function () {
            return new LogSession(Storage::disk(config('filesystems.cloud')));
        });

        $this->app->singleton('TaskResult', TaskResult::class);
        $this->app->singleton('TaskState', TaskState::class);

        $this->app->bind(TaskRepository::class, EloquentTaskRepository::class);
    }
}
