<?php


namespace Label305\Tasks\Http\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;
use Label305\Tasks\Persistence\Tasks\TaskRepository;
use Label305\Tasks\DispatchesTasks;
use Label305\Tasks\Enums\State;
use League\Flysystem\Adapter\Local;

class TasksController extends Controller
{

    use DispatchesTasks;

    public function log(int $id, TaskRepository $taskRepository)
    {
        $task = $taskRepository->findById($id);

        if ($task === null) {
            abort(404);
        }

        if ($task->getState() !== State::FINISHED) {
            if (!file_exists($task->getLocalPathForLog())) {
                return response('No log found.. yet.', 200, ['Content-Type' => 'text/plain']);
            }

            return response()->file($task->getLocalPathForLog());
        }

        $cloudDisk = Storage::disk(config('filesystems.cloud'));
        if (!$cloudDisk->exists($task->getPersistentPathForLog())) {
            return response('No log found in cloud.. yet.', 200, ['Content-Type' => 'text/plain']);
        }

        if ($cloudDisk->getDriver()->getAdapter() instanceof Local) {
            return response()->file($cloudDisk->path($task->getPersistentPathForLog()));
        }

        $url = $cloudDisk->url($task->getPersistentPathForLog());
        return redirect()->to($url);
    }

    public function finish(int $taskId, TaskRepository $taskRepository){

        $task = $taskRepository->findById($taskId);
        $task->setState(State::FINISHED);
        $taskRepository->store($task);

        return redirect()->route('tasks.show', ['id' => $task->getId()]);
    }

    public function longRunning(int $taskId, TaskRepository $taskRepository)
    {
        $task = $taskRepository->findById($taskId);
        $task->setIsLongRunning(true);
        $taskRepository->store($task);

        return redirect()->back();
    }
}
