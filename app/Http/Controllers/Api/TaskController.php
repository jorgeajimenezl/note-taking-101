<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ErrorResponse;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::where('author_id', auth()->id())->paginate(10);
        $sharedTasks = Task::select('tasks.*')
            ->join('contributors', 'tasks.id', '=', 'contributors.task_id')
            ->where('contributors.user_id', auth()->id())
            ->paginate(10);

        return new TaskCollection($tasks->merge($sharedTasks));
    }

    public function show(string $id)
    {
        $task = Task::with('author', 'contributors', 'tags')->find($id);

        if ($task === null) {
            return ErrorResponse::notFound();
        }

        if ($task->getUserRole(auth()->user()) === null) {
            return ErrorResponse::unauthorized();
        }

        return new TaskResource($task);
    }

    public function destroy(string $id)
    {
        $task = Task::find($id);

        if ($task === null) {
            return ErrorResponse::notFound();
        }

        if ($task->author_id !== auth()->id()) {
            return ErrorResponse::unauthorized();
        }

        $task->delete();

        return response()->noContent();
    }
}
